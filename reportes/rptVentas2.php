<?php
error_reporting(0);


	session_start();
	$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_id_periodo_contable = $_SESSION['sesion_id_periodo_contable'];
    $sesion_empresa_ruc = $_SESSION['sesion_empresa_ruc'];
    $sesion_ciudades_ciudad = $_SESSION['sesion_ciudades_ciudad'];
	$sesion_empresa_direccion = $_SESSION['sesion_empresa_direccion'];
		// $sesion_empresa_telefono = $_SESSION['sesion_empresa_telefono1'];
// 		$sesion_codigo_lug = $_SESSION['userest'];
// 	$sesion_codigo_lug = isset($_SESSION['sesion_codigo_lug'])?$_SESSION['sesion_codigo_lug']:0;
function ceros($valor){
	$s='';
 for($i=1;$i<=9-strlen($valor);$i++)
	 $s.="0";
 return $s.$valor;
}
 
       $matrizador= $_GET['matrizador'];
       if(trim($matrizador)==''){
           $matrizador=0;
       }
       $numero_libro= $_GET['numero_libro'];
       if(trim($numero_libro)==''){
           $numero_libro=0;
       }
       
	require_once('../conexion.php');
	require_once('class.ezpdf.php');
    date_default_timezone_set("America/Guayaquil");
    $pdf =& new Cezpdf('a4','Landscape');

    $pdf->selectFont('fonts/courier.afm');
    $pdf->ezSetCmMargins(1,1,1.5,1.5);
    $datacreador = array (
    	'Title'=>'Facturas',
    	'Subject'=>'Saldo Inicial',
    	'Author'=>'25 de junio',
    	'Producer'=>'Macarena Lalama'
    	);
	
$pdf->addInfo($datacreador);
$pdf->ezText("<b>Fecha actual:</b> ".date("d/m/Y"), 10,array( 'justification' => 'right' ));

	$fecha_desde = ($_GET['txtFechaDesde']);
    $fecha_hasta = ($_GET['txtFechaHasta']);
    $ciudad= ($_GET['cbciudad']);
    $tipoDoc= ($_GET['tipoDoc']);
    $txtUsuarios= $_GET['txtUsuarios'];
    $estado= $_GET['estado'];
	$areas= $_GET['areas'];
	$autorizacion= $_GET['autorizacion'];
	$txtClientes = $_GET['txtClientes']; 
	$emision = $_GET['emision'];
	
    if($areas!='0'){
		$sqlArea="SELECT `id_centro_costo`, `descripcion` FROM `centro_costo` WHERE id_centro_costo=$areas ";
		$resultArea = mysql_query($sqlArea) or die(mysql_error());
		while($rowArea = mysql_fetch_array($resultArea)){
			$nombreArea = $rowArea['descripcion'];
		}
		$nombreArea = strtoupper($nombreArea);
		$pdf->ezText("<b>REPORTE DE VENTAS DE ".utf8_decode($nombreArea)." </b>", 18,array( 'justification' => 'center' ));

	}else{
		$pdf->ezText("<b>REPORTE DE VENTAS</b>", 18,array( 'justification' => 'center' ));
	}
    

	$pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 14,array( 'justification' => 'center' ));
	$pdf->ezText("<b>Desde: ".$fecha_desde."    Hasta: ".$fecha_hasta."</b>\n", 12,array( 'justification' => 'center' ));

    $listadoSubtotales = array();
    $sqlImpuestos = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='".$sesion_id_empresa."'";
      $resulImpuestos = mysql_query($sqlImpuestos);
      $listadoImpuestos = array();
      $existe12= false;
      while($rowIv = mysql_fetch_array($resulImpuestos) ){
          
          $listadoImpuestos['id_iva'][]= $rowIv['id_iva'];
          $listadoImpuestos['iva'][]= $rowIv['iva'];
          $idiva = $rowIv['id_iva'];
           $listadoSubtotales[$idiva] = 0;
           
          $sumaBase[$idiva]=0;
          if($rowIv['iva']=='12'){
                  $existe12=true;
              }
      }
      if($existe12==false){
          $sqlImpuestos2 = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='0'";
          $resultImpuestos2 = mysql_query($sqlImpuestos2);
          while($rowImp2 = mysql_fetch_array($resultImpuestos2) ){
            $listadoImpuestos['id_iva'][]= $rowImp2['id_iva'];
            $listadoImpuestos['iva'][]= $rowImp2['iva'];
            $iva_ac = $rowImp2['id_iva'];
            $sumaBase[$iva_ac]=0;
            $listadoSubtotales[$iva_ac] = 0;
              }
      }
           $cantidadImpuestos = count($listadoImpuestos['iva']);

           
	$sqlCentrosCostos="SELECT id_centro_costo ,descripcion FROM `centro_costo` WHERE `empresa`=$sesion_id_empresa";
	if($areas!='0'){
		$sqlCentrosCostos .=" and id_centro_costo=$areas";
	}

	$resultCentrosCostos = mysql_query($sqlCentrosCostos);
	$cc=0;
	$contadorfilas=0;
	$result = array();
		
	$sqlVentasSinDetalle="SELECT ventas.id_empresa, ventas.id_venta, ventas.numero_factura_venta,
	ventas.fecha_venta, detalle_ventas.id_detalle_venta, COUNT(detalle_ventas.id_detalle_venta) as total, ventas.fecha_anulacion 
	from ventas LEFT JOIN detalle_ventas on detalle_ventas.id_venta = ventas.id_venta WHERE detalle_ventas.id_detalle_venta 
	is null and ventas.id_empresa=$sesion_id_empresa  AND
	 DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d') >='".$_GET['txtFechaDesde']."' and
	 DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d')<='".$_GET['txtFechaHasta']."' GROUP by ventas.id_venta ORDER BY `ventas`.`fecha_venta` DESC";
	$resultVentasSinDetalle= mysql_query($sqlVentasSinDetalle);
	$numFilasSinDetalle = mysql_num_rows($resultVentasSinDetalle);
	$listaSD= '0';
	if($numFilasSinDetalle>0){
	    while($rowSD= mysql_fetch_array($resultVentasSinDetalle)){
	        $listaSD.= ','.$rowSD['id_venta'];
	    }
	}
	
	while($rowCC = mysql_fetch_array($resultCentrosCostos)){
		$sql='';
		$sql = "SELECT
		ventas.`id_empresa` AS ventas_id_empresa,
		ventas.`id_venta` AS ventas_id_venta,
		ventas.`numero_factura_venta` AS ventas_numero_factura_venta,
		ventas.`id_cliente` AS ventas_id_cliente,
		ventas.`fecha_venta` AS ventas_fecha_venta,
		ventas.`sub_total` AS ventas_sub_total,
		ventas.`sub0` AS ventas_sub0,
		ventas.`sub12` AS ventas_sub12,
		ventas.`id_iva` AS ventas_id_iva,

	
		ventas.`codigo_pun` AS codigo_pun,
		ventas.`codigo_lug` AS codigo_lug,	
		ventas.`estado` AS estado,
		ventas.`tipo_documento` AS tipo_documento,

		SUM(detalle_ventas.`v_total`) AS  ventas_total,
		SUM(detalle_ventas.`total_iva`) AS  ventas_total_iva,
		detalle_ventas.`cantidad` AS  detalle_ventas_cantidad,
		detalle_ventas.`v_unitario` AS  detalle_ventas_vUnitario,
		detalle_ventas.`descuento` AS  detalle_ventas_descuento,
		
		establecimientos.`codigo` AS codigo_est,
		emision.`codigo` AS codigo_emi,
		
		ventas.`numero_factura_venta` as ventas_numero_factura_venta,
		ventas.`id_forma_pago` AS ventas_id_forma_pago,
		clientes.`id_cliente` AS clientes_id_cliente,
		clientes.`nombre` AS clientes_nombre,
		clientes.`apellido` AS clientes_apellido,
		clientes.`cedula` AS clientes_cedula,

		productos.producto,
		productos.iva as productos_iva,
	
		cc.descripcion as centro_nombre
	   FROM
	   `ventas` ventas 
	   INNER JOIN `clientes` clientes      ON ventas.`id_cliente` = clientes.`id_cliente` 
	   INNER JOIN `establecimientos` establecimientos      ON establecimientos.`id` = ventas.`codigo_pun` 
	   INNER JOIN `emision` emision      ON ventas.`codigo_lug` = emision.`id` 
	   INNER JOIN  detalle_ventas ON detalle_ventas.`id_venta` = ventas.`id_venta` 
	   INNER JOIN productos on productos.id_producto =  detalle_ventas.`id_servicio`
	   INNER JOIN (Select id_centro_costo, descripcion from centro_costo where empresa =$sesion_id_empresa) as cc ON
	   cc.id_centro_costo = detalle_ventas.idBodega
			";
			

		$sql .= " where ventas.`id_empresa`='".$sesion_id_empresa."' 
		AND
		DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d') >='".$_GET['txtFechaDesde']."' and
		DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d')<='".$_GET['txtFechaHasta']."'  ";
		
	
	   if ($estado!='0'){
		   $sql.= "and ventas.`estado`='".$estado."' "; 
	   }  

	   if ($emision !='0'&& $emision!='GLOBAL'){
		$sql.= " and emision.`id` ='".$emision."' "; 
		}

	   if ($txtClientes!='0' ){
		$sql.= " and clientes.`id_cliente` = '".$txtClientes."' "; 
		}
		
	   if ($ciudad!=0){
		   $sql.= "and clientes.`id_ciudad`='".$ciudad."' "; 
	   }
	   
	   if ($tipoDoc!=0){
		   $sql.= "and ventas.`tipo_documento`='".$tipoDoc."' "; 
	   }
	   
	   
	   if ($txtUsuarios!=0){
		   $sql.= "and ventas.`id_usuario`='".$txtUsuarios."' "; 
	   }
	   	$vendedor_id = trim($_GET['vendedor']);
if ($vendedor_id!=0 && $vendedor_id!=''){
       $sql.= " and clientes.`id_vendedor`='".$vendedor_id."' "; 
   }
   
	   if ($autorizacion =='1'){
		$sql.= " and ventas.`Autorizacion`!='' "; 
    	}else if ($autorizacion =='2'){
    	    	$sql.= " and ventas.`Autorizacion` IS NULL "; 
    	}
   
	
		   $sql.= "and detalle_ventas.`idBodega`='".$rowCC['id_centro_costo']."' "; 
	  
	   
		   $sql.= " and ventas.id_venta NOT IN (".$listaSD.")   GROUP BY ventas.id_venta ORDER BY ventas.numero_factura_venta ".$_GET['orden']." LIMIT 5 ;"; 

    //  echo $sql;  
   
if($sesion_id_empresa==41){
    //  echo $sql;  
}
	   $result[$cc] = mysql_query($sql) or die(mysql_error());
	   $numeroFilasComsulta = mysql_num_rows( $result[$cc] );

	   $nombreCentro = '';
	   $titles = array(
		   '#' => '<b>#</b>',
		   'fecha' => '<b>Fecha</b>',
		   
		   'numero_factura_venta' => '<b>No. de Factura</b>',
		   'identificacion' => utf8_decode('<b>Identificacion</b>'),
		   'nombre' => '<b>Cliente</b>',
		   'forma_pago' => '<b>Detalle</b>',
		   
		   'cantidad' => '<b>Cantidad</b>',
		   'valorUnitario' => '<b>Valor Unitario</b>',
		   'descuento' => '<b>Descuento</b>',
		   'sub_total' => '<b>Sub_total</b>',
		   'iva' => '<b>IVA </b>',
		   'total' => '<b>Total Neto</b>'
		   
	   );
	   $sumaSubtotales = array();	
       for($e =0; $e<$cantidadImpuestos; $e++){
        $idiva = $listadoImpuestos['id_iva'][$e];
        $sumaSubtotales[$idiva] = 0;
           $titles[$idiva]=  '<b>Subtotal '.$listadoImpuestos['iva'][$e].' %</b>';
    }
    //   var_dump($titles);
				$options = array
				(
						'shadeCol'=>array(0.9,0.9,0.9),
						'xOrientation'=>'center',
						'width'=>800,
						
						'cols'=>array(
						'#'=>array('justification'=>'left','width'=>20),
					   
						'numero_factura_venta'=>array('justification'=>'left','width'=>50),
						 'identificacion'=>array('justification'=>'left','width'=>45),
						'nombre'=>array('justification'=>'left','width'=>90),
						'forma_pago'=>array('justification'=>'left','width'=>90),
						'fecha'=>array('justification'=>'center','width'=>50),
						'sub_total'=>array('justification'=>'right','width'=>60),
						'total'=>array('justification'=>'right','width'=>70),
						'cantidad'=>array('justification'=>'right','width'=>40),
						'descuento'=>array('justification'=>'right','width'=>45),
						'valorUnitario'=>array('justification'=>'right','width'=>75),
						'iva'=>array('justification'=>'right','width'=>40)
						)
				 
				);
			
			
				$options['fontSize']= 7;


				if($numeroFilasComsulta>0){

			
	   while($row = mysql_fetch_array($result[$cc])) //for mayor    for($i=0; $i<$numero_filas; $i++)
	   {
	        
        $ventas_id_venta = $row["ventas_id_venta"];
        
        $sqlSubTotalesVta="SELECT `id_detalle_venta`, `idBodega`, `idBodegaInventario`, `cantidad`, `estado`, `v_unitario`, `descuento`, SUM(`v_total`) as total_producto, `id_venta`, `id_servicio`, `detalle`, `id_kardex`, `tipo_venta`, `id_empresa`, `id_proyecto`, `tarifa_iva`, SUM(`total_iva`) as suma_iva FROM `detalle_ventas` WHERE id_venta = '".$ventas_id_venta."' GROUP BY tarifa_iva;";
	    $resultSubTotalesVta = mysql_query($sqlSubTotalesVta);
	    $listadoSubtotales = array();
	    $listadoIva = array();
	    $suma_iva_actual=0;
	    $suma_subtotales_actual=0;
	    while($rowSub = mysql_fetch_array($resultSubTotalesVta) ){
	        $id_actual = $rowSub['tarifa_iva'] ;
	        $listadoSubtotales[$id_actual] = $rowSub['total_producto'] ;
	        $suma_iva_actual+= $rowSub['suma_iva'] ;
	         $suma_subtotales_actual+= $rowSub['total_producto'] ;
	    }

            $ventas_total_iva = $row['ventas_total_iva'];
		   $iva = $row['ventas_id_iva'];
		   $formasPago = $row["producto"];
		   
		   $numeroFactura= $row['ventas_numero_factura_venta'];
		   $numeroFactura= ceros($numeroFactura);
		   $valorRF=0;
		   $valorRI=0;
   
		   $numero ++;

			$sqlu="Select valor From cobrospagos where id_factura='".$ventas_id_venta."' and tipo='retencion-fuente';";
			   $resultu=mysql_query($sqlu);
			   while($rowu=mysql_fetch_array($resultu))
				{   $valorRF = $rowu["valor"];}
				
		   $sqli="Select valor From cobrospagos where id_factura='".$ventas_id_venta."' and tipo='retencion-iva';";
			   $resulti=mysql_query($sqli);
			   while($rowi=mysql_fetch_array($resulti))
				{ $valorRI = $rowi["valor"];}

    $descuento = $row["detalle_ventas_descuento"] ;
   
		  $subtotale = $suma_subtotales_actual?number_format($suma_subtotales_actual, 2, '.', ' ') :0;
		  
		  $valor_limpio = str_replace(array(' ', ','), '', $subtotale);
        $valor_decimal = floatval($valor_limpio);

		  $calculoIva =$suma_iva_actual;
		  $totale = $valor_decimal + $calculoIva;

    
    if($row["estado"]!='Activo'){
       $subtotale = 0;
       $calculoIva=0;
       $totale=0;
       $valor_decimal=0;
       $descuento = 0;
   }
   
		  $sumaIvaFinal = $sumaIvaFinal +$calculoIva;
		  $sumaDesuentos = $sumaDesuentos +$descuento;
		  $suma_base12 = $suma_base12 + $row["detalle_ventas_vUnitario"];//suma de base12
		  $suma_base0 = $suma_base0 + $row["detalle_ventas_cantidad"];//suma de base0
		  $subtotal_venta = $subtotal_venta + $valor_decimal;//suma de subtotal
		  $total_venta = $total_venta + $totale; //suma de total

	   //    "FACTURA # ".$estCod."-".$emiCod."-".$numeroFactura
	   $data[$cc][]  = array(
		   '#'=>$numero,
		   'numero_factura_venta'=> $row['codigo_est']."-".$row['codigo_emi']."-".$numeroFactura,
		   'identificacion'=>  $row["clientes_cedula"] ,
		   'nombre'=>   utf8_decode($row["clientes_nombre"]." ".$row["clientes_apellido"]),
		   
		   'forma_pago'=> utf8_decode($formasPago) ,
		   'cantidad'=>$row["detalle_ventas_cantidad"] ,
		   'valorUnitario'=>number_format($row["detalle_ventas_vUnitario"], 2, '.', ' '), 
		   'descuento'=>$descuento ,
		   'fecha'=>($row["ventas_fecha_venta"]) ,
		   'sub_total'=>$valor_decimal,
		   'iva'=>number_format($suma_iva_actual, 2, '.', ' '),
		   'total'=>number_format($totale, 2, '.', ' ')
		   );
        $filaActual = count($data[$cc])-1;
        for($e =0; $e<$cantidadImpuestos; $e++){
            $idiva = $listadoImpuestos['id_iva'][$e];
    	    if(isset($listadoSubtotales[$idiva]) && $row["estado"]=='Activo'){
    	         $sumaSubtotales[$idiva] +=  $listadoSubtotales[$idiva];
    	        	$data[$cc][$filaActual][$idiva]=  number_format($listadoSubtotales[$idiva], 2, '.', ' ') ;
    	    }else{
    	       
    	        	$data[$cc][$filaActual][$idiva]=  number_format(0, 2, '.', ' ') ;
    	    }
        }
        // var_dump($data[$cc]);
        // exit;
	   }

	 
	   $data[$cc][] = array(
	
	
		'nombre'=> 'TOTAL' ,
		'total'=>number_format($total_venta, 2, '.', ' '),
	
		'descuento'=> number_format($sumaDesuentos, 2, '.', ' ') ,
		'iva'=>number_format($sumaIvaFinal, 2, '.', ' ') ,
		'sub_total'=>number_format($subtotal_venta, 2, '.', ' '),
		
	 
		);
		   $filaActual = count($data[$cc])-1;
        for($e =0; $e<$cantidadImpuestos; $e++){
            $idiva = $listadoImpuestos['id_iva'][$e];
    	    if(isset($sumaSubtotales[$idiva]) ){
    	        	$data[$cc][$filaActual][$idiva]=  number_format($sumaSubtotales[$idiva], 2, '.', ' ') ;
    	    }else{
    	        	$data[$cc][$filaActual][$idiva]=  number_format(0, 2, '.', ' ') ;
    	    }
        }

		$total_venta=0;
				   $suma_base0=0;
				   $suma_base12=0;
				   $sumaIvaFinal=0;
				   $subtotal_venta=0;
				
				   $sumaDesuentos=0;
		
		$txttit.= "";
        $pdf->ezText($txttit, 12);
        $pdf->ezText("<b>".utf8_decode($rowCC['descripcion'])."</b>", 18,array( 'justification' => 'center' ));
        $pdf->ezText("\n", 10);
        $pdf->ezTable($data[$cc], $titles, '', $options);
	}
		$cc++;

	}

	$pdf->ezStartPageNumbers(550, 80, 10);
	//$nombrearchivo = "reporteLibroMayor.pdf";
	$pdf->ezStream();
	//$pdf->ezOutput($nombrearchivo);
	$pdf->Output('reporteVentas.pdf', 'D');

	mysql_close();
	mysql_free_result($result);

?>
