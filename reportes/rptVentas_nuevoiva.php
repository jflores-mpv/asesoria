<?php
error_reporting(0);


	session_start();
	$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_id_periodo_contable = $_SESSION['sesion_id_periodo_contable'];
    $sesion_empresa_ruc = $_SESSION['sesion_empresa_ruc'];
    $sesion_ciudades_ciudad = $_SESSION['sesion_ciudades_ciudad'];
	$sesion_empresa_direccion = $_SESSION['sesion_empresa_direccion'];

	  $dominio = $_SERVER['SERVER_NAME'];
       $matrizador= $_GET['matrizador'];
       if(trim($matrizador)==''){
           $matrizador=0;
       }
       $numero_libro= $_GET['numero_libro'];
       if(trim($numero_libro)==''){
           $numero_libro=0;
       }
        // $matrizador=0;
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
	$emision = $_GET['emision'];
    $autorizacion= $_GET['autorizacion'];
    $txtClientes = $_GET['txtClientes']; 
    
    if ($estado =='Pasivo'){
        $titulo ="ANULADAS";
    }else {
        $titulo='';
    }

	$pdf->ezText("<b>REPORTE DE VENTAS ".$titulo."</b>", 18,array( 'justification' => 'center' ));
	$pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 14,array( 'justification' => 'center' ));
	$pdf->ezText("<b>Desde: ".$fecha_desde."    Hasta: ".$fecha_hasta."</b>\n", 12,array( 'justification' => 'center' ));
	
	
	$sqlVentasSinDetalle="SELECT ventas.id_empresa, ventas.id_venta, ventas.numero_factura_venta, ventas.fecha_venta, detalle_ventas.id_detalle_venta, COUNT(detalle_ventas.id_detalle_venta) as total, ventas.fecha_anulacion 
	from ventas LEFT JOIN detalle_ventas on detalle_ventas.id_venta = ventas.id_venta WHERE detalle_ventas.id_detalle_venta is null and ventas.id_empresa=$sesion_id_empresa  AND
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
	 ventas.`total` AS ventas_total,
	 ventas.`codigo_pun` AS codigo_pun,
	 ventas.`codigo_lug` AS codigo_lug,
	ventas.`descuento` AS ventas_descuento,
	 
	 ventas.`total_iva` AS ventas_total_iva,
	 ventas.`estado` AS ventas_estado,
	 ventas.`tipo_documento` AS tipo_documento,
	 
	 establecimientos.`codigo` AS codigo_est,
	 emision.`codigo` AS codigo_emi,
	 
	 ventas.`numero_factura_venta` as ventas_numero_factura_venta,
	 ventas.`id_forma_pago` AS ventas_id_forma_pago,
	 clientes.`id_cliente` AS clientes_id_cliente,
     clientes.`nombre` AS clientes_nombre,
     clientes.`apellido` AS clientes_apellido,
     clientes.`cedula` AS clientes_cedula,
     vendedores.nombre as vendedor_nombre,
       vendedores.apellidos as vendedor_apellido
	FROM
	`ventas` ventas 
	INNER JOIN `clientes` clientes      ON ventas.`id_cliente` = clientes.`id_cliente` 
	INNER JOIN `establecimientos` establecimientos  ON establecimientos.`id` = ventas.`codigo_pun` 
	INNER JOIN `emision` emision      ON ventas.`codigo_lug` = emision.`id` 
	
	INNER JOIN detalle_ventas ON detalle_ventas.id_venta =ventas.id_venta
	INNER JOIN productos on productos.id_producto =  detalle_ventas.`id_servicio`
	LEFT JOIN vendedores on vendedores.id_vendedor =  ventas.`vendedor_id_tabla`
	 ";
	
     if ( ($matrizador!='0'||$numero_libro!='0') && ($dominio=='jderp.cloud' || $dominio=='www.jderp.cloud') ){
     if ($matrizador!='0'){
         $sql.= " INNER JOIN(
            SELECT
                `id_info_adicional`,
                `campo`,
                `descripcion`,
                `id_venta`,
                `id_empresa`
            FROM
                `info_adicional`
            WHERE
                id_empresa = '".$sesion_id_empresa."' AND
                    info_adicional.campo = 'Matrizador' AND info_adicional.`descripcion` = '".$matrizador."'
               
        ) AS adicional
        ON
            adicional.id_venta = ventas.id_venta";
     }     
    
       if ($numero_libro!='0'){
            $sql.= " INNER JOIN(
                SELECT
                    `id_info_adicional`,
                    `campo`,
                    `descripcion`,
                    `id_venta`,
                    `id_empresa`
                FROM
                    `info_adicional`
                WHERE
                    id_empresa = '".$sesion_id_empresa."' AND 
                        info_adicional.campo = 'NUMERO DE LIBRO' AND info_adicional.`descripcion` = '".$numero_libro."'
            ) AS adicional2
            ON
                adicional2.id_venta = ventas.id_venta";
           
       }
    
        
  }  
   
     $sql .= " where ventas.`id_empresa`='".$sesion_id_empresa."'  AND
	 DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d') >='".$_GET['txtFechaDesde']."' and
	 DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d')<='".$_GET['txtFechaHasta']."'  ";
	 

   
	if ($ciudad!='0' && $ciudad!='' ){
		$sql.= " and clientes.`id_ciudad`='".$ciudad."' "; 
	}
	if ($txtClientes!='0' ){
		$sql.= " and clientes.`id_cliente` = '".$txtClientes."' "; 
	}
	if ($tipoDoc!='0'){
		$sql.= " and ventas.`tipo_documento`='".$tipoDoc."' "; 
	}
	
	
	if ($txtUsuarios!='0'){
		$sql.= " and ventas.`id_usuario`='".$txtUsuarios."' "; 
	}
	
	
	
	if ($estado !='0'){
		$sql.= " and ventas.`estado`='".$estado."' "; 
	}
	if ($emision !='0' && $emision!='GLOBAL' ){
		$sql.= " and emision.`id` ='".$emision."' "; 
	}
	
	if ($autorizacion =='1'){
		$sql.= " and ventas.`Autorizacion`!='' "; 
	}else if ($autorizacion =='2'){
	    	$sql.= " and ventas.`Autorizacion` IS NULL "; 
	}
	$vendedor_id = trim($_GET['vendedor']);
if ($vendedor_id!=0 && $vendedor_id!=''){
       $sql.= " and ventas.`vendedor_id_tabla`='".$vendedor_id."' "; 
   }
	
		$sql.= " and ventas.id_venta NOT IN (".$listaSD.")  GROUP BY ventas.id_venta ORDER BY ventas.codigo_pun, ventas.numero_factura_venta ".$_GET['orden']."    ;"; 

	
		  function ceros($valor){
			$s='';
		 for($i=1;$i<=9-strlen($valor);$i++)
			 $s.="0";
		 return $s.$valor;
	 }
	 
	 $sumaDescuento= 0;
	$sqlImpuestos = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='".$sesion_id_empresa."'";
	$resulImpuestos = mysql_query($sqlImpuestos);
	$listadoImpuestos = array();
	$existe12= false;
	while($rowIv = mysql_fetch_array($resulImpuestos) ){
	    $listadoImpuestos['id_iva'][]= $rowIv['id_iva'];
	    $listadoImpuestos['iva'][]= $rowIv['iva'];
	    $idiva = $rowIv['id_iva'];
	    
	    if($rowIv['iva']=='12'){
	    	    $existe12=true;
	    	}
	}
	if($existe12==false){
	    $sqlImpuestos2 = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='0";
	    $resultImpuestos2 = mysql_query($sqlImpuestos2);
	    while($rowImp2 = mysql_fetch_array($resultImpuestos2) ){
	    	    $listadoImpuestos['id_iva'][]= $rowImp2['id_iva'];
	    $listadoImpuestos['iva'][]= $rowImp2['iva'];
	    	}
	}
	 $cantidadImpuestos = count($listadoImpuestos['iva']);
    $codigoPun=0;
     if($dominio=='jderp.cloud' or $dominio=='www.jderp.cloud'or $dominio=='contaweb.ec' or $dominio=='www.contaweb.com.ec' or $dominio=='www.contaweb.ec'){
         $titles = array(
		'#' => '<b>#</b>',
		'fecha' => '<b>Fecha</b>',
		
		'numero_factura_venta' => '<b>No. de Factura</b>',
		'identificacion' => '<b>Identificacion</b>',
		'nombre' => '<b>Cliente</b>',
		'nombreVendedor' => '<b>Vendedor</b>',
		'forma_pago' => '<b>Formas de pago</b>',
		'descuento' => '<b>Descuento</b>',
		'sub_total' => '<b>Sub_total</b>',
		'iva' => '<b>IVA '.$valorIva.'% </b>',
		'total' => '<b>Total Neto</b>'
// 		'retFuente' => '<b>Ret. Fuente</b>',
// 		'retIva' => '<b>Ret. IVA</b>'
		
	);

	

        for($e =0; $e<$cantidadImpuestos; $e++){
            $idiva = $listadoImpuestos['id_iva'][$e];
    	   	$titles[$idiva]=  '<b>Base '.$listadoImpuestos['iva'][$e].' %</b>';
        }

	
     }else{
        $titles = array(
		'#' => '<b>#</b>',
		'fecha' => '<b>Fecha</b>',
		
		'numero_factura_venta' => '<b>No. de Factura</b>',
		'identificacion' => '<b>Identificacion</b>',
		'nombre' => '<b>Cliente</b>',
		'forma_pago' => '<b>Formas de pago</b>',
		
	
		'descuento' => '<b>Descuento</b>',
		'sub_total' => '<b>Sub_total</b>',
		'iva' => '<b>IVA '.$valorIva.'% </b>',
		'total' => '<b>Total Neto</b>'
// 		'retFuente' => '<b>Ret. Fuente</b>',
// 		'retIva' => '<b>Ret. IVA</b>'
		
	); 

        for($e =0; $e<$cantidadImpuestos; $e++){
            $idiva = $listadoImpuestos['id_iva'][$e];
    	   	$titles[$idiva]=  '<b>Base '.$listadoImpuestos['iva'][$e].' %</b>';
        }
        
		
     }
    	
		
		 //tercera    
	if($dominio=='jderp.cloud' or $dominio=='www.jderp.cloud'or $dominio=='contaweb.ec' or $dominio=='www.contaweb.com.ec' or $dominio=='www.contaweb.ec'){
	    	$options = array(
			'shadeCol'=>array(0.9,0.9,0.9),
			'xOrientation'=>'center',
			'width'=>800,
			
			'cols'=>array(
			'#'=>array('justification'=>'left','width'=>20),
		   
			'numero_factura_venta'=>array('justification'=>'left','width'=>60),
			 'identificacion'=>array('justification'=>'left','width'=>70),
			'nombre'=>array('justification'=>'left','width'=>60),
			'nombreVendedor'=>array('justification'=>'left','width'=>60),
			'forma_pago'=>array('justification'=>'left','width'=>70),
			'fecha'=>array('justification'=>'center','width'=>50),
			'sub_total'=>array('justification'=>'right','width'=>75),
			'total'=>array('justification'=>'right','width'=>75),
			
			'descuento'=>array('justification'=>'right','width'=>45),
			'iva'=>array('justification'=>'right','width'=>45)
			)
	
	 );	  
// 	 $sqlImpuestos = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='".$sesion_id_empresa."' ";
// 	$resulImpuestos = mysql_query($sqlImpuestos);
// 	while($rowIv = mysql_fetch_array($resulImpuestos) ){
// 	    $idiva = $rowIv['id_iva'];
// 	    	$options['cols'][$idiva]['justification']=  'right';
// 	    	$options['cols'][$idiva]['width']=  '60';
// 	}	
    }else{
        	$options = array(
			'shadeCol'=>array(0.9,0.9,0.9),
			'xOrientation'=>'center',
			'width'=>800,
			
			'cols'=>array(
			'#'=>array('justification'=>'left','width'=>20),
		   
			'numero_factura_venta'=>array('justification'=>'left','width'=>60),
			 'identificacion'=>array('justification'=>'left','width'=>80),
			'nombre'=>array('justification'=>'left','width'=>60),
			'forma_pago'=>array('justification'=>'left','width'=>70),
			'fecha'=>array('justification'=>'center','width'=>50),
			'sub_total'=>array('justification'=>'right','width'=>75),
			'total'=>array('justification'=>'right','width'=>75),
			'descuento'=>array('justification'=>'right','width'=>45),
			'iva'=>array('justification'=>'right','width'=>45)
			)
			);
// 			 $sqlImpuestos = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='".$sesion_id_empresa."' ";
// 	$resulImpuestos = mysql_query($sqlImpuestos);
// 	while($rowIv = mysql_fetch_array($resulImpuestos) ){
// 	    $idiva = $rowIv['id_iva'];
// 	    	$options['cols'][$idiva]['justification']=  'right';
// 	    	$options['cols'][$idiva]['width']=  '60';
// 	}
    }

	$options['fontSize']= 7;
	$numero=0;
	$numeroProducto=0;
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result)) //for mayor    for($i=0; $i<$numero_filas; $i++)
	{
	     $ventas_id_venta = $row["ventas_id_venta"];
	     	$suma_total_iva = $row['ventas_total_iva'];
	     	
	    $sqlSubTotalesVta="SELECT `id_detalle_venta`, `idBodega`, `idBodegaInventario`, `cantidad`, `estado`, `v_unitario`, `descuento`, SUM(`v_total`) as total_producto, `id_venta`, `id_servicio`, `detalle`, `id_kardex`, `tipo_venta`, `id_empresa`, `id_proyecto`, `tarifa_iva`, SUM(`total_iva`) as suma_iva FROM `detalle_ventas` WHERE id_venta = '".$ventas_id_venta."' GROUP BY tarifa_iva;";
	    $resultSubTotalesVta = mysql_query($sqlSubTotalesVta);
	    $listadoSubtotales = array();
	    $listadoIva = array();
	    while($rowSub = mysql_fetch_array($resultSubTotalesVta) ){
	        $id_actual = $rowSub['tarifa_iva'] ;
	        $listadoSubtotales[$id_actual] = $rowSub['total_producto'] ;
	    }
	   
	    if($numero==0){
	          $codigoPun= $row['codigo_emi'];
	    }
	
	
		$sqlFormaPago="SELECT
   cobrospagos.id,
   cobrospagos.documento,
   cobrospagos.id_factura,
     formas_pago.`id_forma_pago` AS formas_pago_id_forma_pago,
     formas_pago.`nombre` AS formas_pago_nombre,
     formas_pago.`id_plan_cuenta` AS formas_pago_id_plan_cuenta,
     formas_pago.`id_empresa` AS formas_pago_id_empresa,
     formas_pago.`id_tipo_movimiento` AS formas_pago_id_tipo_movimiento,
     tipo_movimientos.`id_tipo_movimiento` AS tipo_movimientos_id_tipo_movimiento,
     tipo_movimientos.`nombre` AS tipo_movimientos_nombre,
     tipo_movimientos.`id_empresa` AS tipo_movimientos_id_empresa
  
FROM
    `formas_pago` 
INNER JOIN `tipo_movimientos` tipo_movimientos ON formas_pago.`id_tipo_movimiento` = tipo_movimientos.`id_tipo_movimiento`
INNER JOIN cobrospagos ON cobrospagos.id_forma = formas_pago.id_forma_pago
     
        where formas_pago.`id_empresa`='".$sesion_id_empresa."'  AND cobrospagos.documento=0   AND cobrospagos.id_factura='".$ventas_id_venta."'
ORDER BY `cobrospagos`.`id` ASC LIMIT 1;";
$resultFormaPago = mysql_query($sqlFormaPago);
$formasPago='';
while($rowFP = mysql_fetch_array($resultFormaPago) ){
    $formasPago = utf8_decode($rowFP['formas_pago_nombre']);
}
// 		$formasPago = ($row["ventas_id_forma_pago"]==1)?'Efectivo':'Transferencia';
		
		$numeroFactura= $row['ventas_numero_factura_venta'];
		$numeroFactura= ceros($numeroFactura);
		$valorRF=0;
		$valorRI=0;

		$numero ++;
	
		
	
		$subtotal= ($row["ventas_estado"]=='Activo')?$row["ventas_sub_total"]:0;
        $total= ($row["ventas_estado"]=='Activo')?$row["ventas_total"]:0;
        $descuentoActual= ($row["ventas_estado"]=='Activo')?$row["ventas_descuento"]:0;
        
        if($row['codigo_emi']!=$codigoPun &&  $emision =='GLOBAL'){
            
            $codigoPun= $row['codigo_emi'];
            if($dominio=='jderp.cloud' or $dominio=='www.jderp.cloud'or $dominio=='contaweb.ec' or $dominio=='www.contaweb.com.ec' or $dominio=='www.contaweb.ec'){        
                $data[] = array(
            		'#'=>'',
            		'nombre'=>'',
            		'nombreVendedor'=> 'TOTAL' ,
            		'total'=>number_format($total_venta, 2, '.', ' ') ,
            		'descuento'=>number_format($sumaDescuento, 2, '.', ' ') ,
            		'iva'=>number_format($sumaIvaFinal, 2, '.', ' ') ,
            		 'sub_total'=>number_format($subtotal_venta, 2, '.', ' '),
            		'retFuente'=>number_format($sumaRF, 2, '.', ' '),
            		'retIva'=>number_format($sumaRI, 2, '.', ' ')
            		);
       $filaActual = count($data)-1;
        for($e =0; $e<$cantidadImpuestos; $e++){
            $idiva = $listadoImpuestos['id_iva'][$e];
    	    if(isset($listadoSubtotales[$idiva]) ){
    	       
    	        	$data[$filaActual][$idiva]=  number_format($listadoSubtotales[$idiva], 2, '.', ' ') ;
    	    }else{
    	       
    	        	$data[$filaActual][$idiva]=  number_format(0, 2, '.', ' ') ;
    	    }
        }
	    
	    
	
            }else{
                $data[] = array(
		'#'=>'',
		'nombre'=> 'TOTAL' ,
		'total'=>number_format($total_venta, 2, '.', ' ') ,
		'descuento'=>number_format($sumaDescuento, 2, '.', ' ') ,
		'iva'=>number_format($sumaIvaFinal, 2, '.', ' ') ,
		 'sub_total'=>number_format($subtotal_venta, 2, '.', ' '),
		'retFuente'=>number_format($sumaRF, 2, '.', ' '),
		'retIva'=>number_format($sumaRI, 2, '.', ' ')
		);
         $filaActual = count($data)-1;
        for($e =0; $e<$cantidadImpuestos; $e++){
            $idiva = $listadoImpuestos['id_iva'][$e];
    	    if(isset($listadoSubtotales[$idiva]) ){
    	        	$data[$filaActual][$idiva]=  number_format($listadoSubtotales[$idiva], 2, '.', ' ') ;
    	    }else{
    	        	$data[$filaActual][$idiva]=  number_format(0, 2, '.', ' ') ;
    	    }
        }
	
            }
        	
        $pdf->ezTable($data, $titles, '', $options);
        $suma_base12 = 0;//suma de base12
		$suma_base0 =0;//suma de base0
		$subtotal_venta = 0;//suma de subtotal
		$total_venta = 0; //suma de total
		$sumaIvaFinal=0;
		$sumaDescuento = 0;
			$numero=1;
		$data=array();
        }
        
		$suma_base12 = $suma_base12 +$sub12;//suma de base12
		$suma_base0 = $suma_base0 + $sub0;//suma de base0
		$subtotal_venta = $subtotal_venta + $subtotal;//suma de subtotal
		$total_venta = $total_venta + $total; //suma de total
		
		 $sqlu="Select valor From cobrospagos where id_factura='".$ventas_id_venta."' and tipo='retencion-fuente';";
	   ///  echo $sqlu;
			$resultu=mysql_query($sqlu);
			while($rowu=mysql_fetch_array($resultu))
			 {   $valorRF = $rowu["valor"];}
			 
		$sqli="Select valor From cobrospagos where id_factura='".$ventas_id_venta."' and tipo='retencion-iva';";
		// echo $sqlu;
			$resulti=mysql_query($sqli);
			while($rowi=mysql_fetch_array($resulti))
			 { $valorRI = $rowi["valor"];}

             

	   $subtotale = isset($subtotal)?$subtotal:0;
	   $totale = isset($total)?$total:0;
	   //$calculoIva = $sub12 *($valorIva/100);
	   $sumaIvaFinal = $sumaIvaFinal +$suma_total_iva;
	   $sumaDescuento =  $sumaDescuento +  $descuentoActual;
	   
	   
	   $numero_factura =($row["ventas_estado"]=='Activo')? $row['codigo_est']."-".$row['codigo_emi']."-".$numeroFactura : '0';
	//    "FACTURA # ".$estCod."-".$emiCod."-".$numeroFactura
	if($dominio=='jderp.cloud' or $dominio=='www.jderp.cloud'or $dominio=='contaweb.ec' or $dominio=='www.contaweb.com.ec' or $dominio=='www.contaweb.ec'){   
	    $data[] = array(
		'#'=>$numero,
		'numero_factura_venta'=> $numero_factura,
		'identificacion'=>  $row["clientes_cedula"] ,
		'nombre'=>  $row["clientes_nombre"]." ".$row["clientes_apellido"],
		'nombreVendedor'=>  $row["vendedor_nombre"]." ".$row["vendedor_apellido"],
		'forma_pago'=>  $formasPago ,
		'descuento'=>$descuentoActual ,
		'fecha'=>($row["ventas_fecha_venta"]) ,
		'sub_total'=>number_format($subtotale, 2, '.', ' '),
		'iva'=>number_format($suma_total_iva, 2, '.', ' '),
		'total'=>number_format($totale, 2, '.', ' '),
		'estado'=> $row["ventas_estado"]
// 		'retFuente'=>number_format($valorRF, 2, '.', ' '),
// 		'retIva'=>number_format($valorRI, 2, '.', ' ')
	 
		);
		$cantidadData = count($data)-1;
		 for($e =0; $e<$cantidadImpuestos; $e++){
		     
            $idiva = $listadoImpuestos['id_iva'][$e];
            

    	    if(isset($listadoSubtotales[$idiva]) ){
    	        
    	        	$data[$cantidadData][$idiva]=  number_format($listadoSubtotales[$idiva], 2, '.', ' ') ;
    	    }else{
    	        
    	        	$data[$cantidadData][$idiva]=  number_format(0, 2, '.', ' ') ;
    	    }
        } 
	}else{
	    $data[] = array(
		'#'=>$numero,
		'numero_factura_venta'=> $numero_factura,
		'identificacion'=>  $row["clientes_cedula"] ,
		'nombre'=>  $row["clientes_nombre"]." ".$row["clientes_apellido"],
		
		'forma_pago'=>  $formasPago ,
		'descuento'=>$descuentoActual ,
		'fecha'=>($row["ventas_fecha_venta"]) ,
		'sub_total'=>number_format($subtotale, 2, '.', ' '),
		'iva'=>number_format($suma_total_iva, 2, '.', ' '),
		'total'=>number_format($totale, 2, '.', ' '),
		'estado'=> $row["ventas_estado"]
// 		'retFuente'=>number_format($valorRF, 2, '.', ' '),
// 		'retIva'=>number_format($valorRI, 2, '.', ' ')
	 
		);
			$cantidadData = count($data)-1;
        for($e =0; $e<$cantidadImpuestos; $e++){
            $idiva = $listadoImpuestos['id_iva'][$e];
    	    if(isset($listadoSubtotales[$idiva]) ){
    	        	$data[$cantidadData][$idiva]=  number_format($listadoSubtotales[$idiva], 2, '.', ' ') ;
    	    }else{
    	        	$data[$cantidadData][$idiva]=  number_format(0, 2, '.', ' ') ;
    	    }
        }
        
	}
// 		var_dump($data);
// 	exit;

		$sumaRF = $ret_fuente + $valorRF;//suma de retencion fuente
		$sumaRI = $ret_iva + $valorRI;//suma de retencion iva
	  
	}

	if($dominio=='jderp.cloud' or $dominio=='www.jderp.cloud'or $dominio=='contaweb.ec' or $dominio=='www.contaweb.com.ec' or $dominio=='www.contaweb.ec'){   
		 $data[] = array(
		'#'=>$numero,
		'nombre'=> '' ,
		'nombreVendedor'=> 'TOTAL' ,
		'total'=>number_format($total_venta, 2, '.', ' ') ,
		'descuento'=>number_format($sumaDescuento, 2, '.', ' ') ,
		'iva'=>number_format($sumaIvaFinal, 2, '.', ' ') ,
		 'sub_total'=>number_format($subtotal_venta, 2, '.', ' '),
		'retFuente'=>number_format($sumaRF, 2, '.', ' '),
		'retIva'=>number_format($sumaRI, 2, '.', ' ')
		);   
		
	}else{
	    $data[] = array(
		'#'=>$numero,
		'nombre'=> 'TOTAL' ,
		'total'=>number_format($total_venta, 2, '.', ' ') ,
		'descuento'=>number_format($sumaDescuento, 2, '.', ' ') ,
		'iva'=>number_format($sumaIvaFinal, 2, '.', ' ') ,
		 'sub_total'=>number_format($subtotal_venta, 2, '.', ' '),
		'retFuente'=>number_format($sumaRF, 2, '.', ' '),
		'retIva'=>number_format($sumaRI, 2, '.', ' ')
		);
	}
	

// var_dump($data);
// 	exit;
	$txttit.= "";
	$pdf->ezText($txttit, 12);
	$pdf->ezTable($data, $titles, '', $options);
	$pdf->ezStartPageNumbers(550, 80, 10);
	$pdf->ezStream();
	$pdf->Output('reporteSaldoInicial.pdf', 'D');

	mysql_close();
	mysql_free_result($result);

?>
