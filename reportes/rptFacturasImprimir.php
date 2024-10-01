<?php

	require('fpdf17/fpdf.php');
	require('../clases/funciones.php');
	require_once('../conexion.php');
	session_start();
//$sesion_empresa_imagen = $_SESSION["sesion_empresa_imagen"];
	
/* 	echo "kk";
	echo $sesion_empresa_imagen;
	echo "<br>";
	echo "din de kk";
	 */
	class PDF extends FPDF
	{
		// Cabecera de página
		function Header()
		{
		//	$pdf->SetXY(1,5);
        		
		//	$pdf->Line(80, 5, 80, 55); // linea vertical entre el elaborado y el revisado
       	
		//	$sesion_empresa_imagen1 = $_SESSION["sesion_empresa_imagen"];
		//	$url1="../images/".$sesion_empresa_imagen1;
		//	$this->Image($url1,12,15,50);
			$this->SetFont('Arial','B',8);
		
			// Movernos a la derecha
			$this->Cell(60);
		//	$this->Cell(80,10,'PEDIDO No.',1,0,'C');
			$this->Ln(20);
		}
		// Pie de página
		function Footer()
		{
			// Posición: a 1,5 cm del final
			$this->SetY(-15);
			// Arial italic 8
			$this->SetFont('Arial','I',8);
			// Número de página
			$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		}
	}
	
	 // Creación del objeto de la clase heredada
    //$pdf = new FPDF('L','mm',array(80,40));
	// 2018-11 $pdf = new PDF('L','mm','A4');
	
//	$pdf = new PDF();
	$pdf = new PDF('P','mm','A4');
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_id_periodo_contable = $_SESSION['sesion_id_periodo_contable'];
    $sesion_empresa_ruc = $_SESSION['sesion_empresa_ruc'];
    $sesion_ciudades_ciudad = $_SESSION['sesion_ciudades_ciudad'];
	$sesion_empresa_direccion = $_SESSION['sesion_empresa_direccion'];
	$sesion_empresa_telefono = $_SESSION['sesion_empresa_telefono1'];
	
	$emision_codigo= $row["emision_codigo"];
	$establecimiento_codigo= $row["establecimiento_codigo"];
//	echo "lugar ->".$establecimiento_codigo;
  
   
    $sql = "SELECT
		ventas.`id_venta` AS ventas_id_venta,
		ventas.`fecha_venta` AS ventas_fecha_venta,
		ventas.`descripcion` AS ventas_descripcion,
		ventas.`id_cliente` AS ventas_id_cliente,
		ventas.`numero_factura_venta` AS ventas_numero_factura_venta,
		ventas.Otros,
		clientes.`id_cliente` AS clientes_id_cliente,
		clientes.`nombre` AS clientes_nombre,
		clientes.`apellido` AS clientes_apellido,
		clientes.`direccion` AS clientes_direccion,
		clientes.`telefono` AS clientes_telefono,
		clientes.`cedula` AS clientes_cedula,
		clientes.`email` AS clientes_email
		FROM
		`ventas` ventas INNER JOIN `clientes` clientes
		ON ventas.`id_cliente` = clientes.`id_cliente` ";
		if ( ($_GET['txtComprobanteNumero']) != ""){
			$sql .= " where ventas.`id_empresa` = '".$sesion_id_empresa."'  AND
			ventas.`id_venta`= '".($_GET['txtComprobanteNumero'])."'  ";               
		}
// echo $sql;
	$result = mysql_query($sql) or die(mysql_error());

    while($row = mysql_fetch_array($result)) 
	{
		$ventas_id_venta = $row["ventas_id_venta"];
        $ventas_fecha_venta = $row["ventas_fecha_venta"];
        $ventas_numero_factura_venta = $row["ventas_numero_factura_venta"];
        $ventas_descripcion = $row["ventas_descripcion"];
		$clientes_id_cliente = $row["clientes_id_cliente"];
		$clientes_cedula = $row["clientes_cedula"];
		$clientes_nombre = $row["clientes_nombre"];
		$clientes_apellido = $row["clientes_apellido"];
		$clientes_direccion = $row["clientes_direccion"];
		$clientes_telefono = $row["clientes_telefono"];
		$clientes_email = $row["clientes_email"];
		$otros = $row["Otros"];
		//echo "entro";
       $ancho_imp=250;
		$alto_imp=190;
        $pdf->AliasNbPages();
        $pdf->AddPage();
		
		/* $pdf->Line(10,166,$ancho_imp,166); // linea debajo de la suma
        $pdf->Line(150,166,150,$alto_imp); // linea vertical de forma de pago
	 */
		$numero_caracteres = strlen($sesion_empresa_nombre); // cuenta cuantos caracteres hay        
      //  $pdf->SetXY($numero_caracteres, 15);
        $pdf->SetFont('Arial', 'B', 10);
		
//		$pdf->Cell(230, 10, $s$col1esion_empresa_nombre, 0,1,'C');
		$pdf->Cell(80, 4, ' ', 0,0);                     
		$pdf->SetFillColor(255,255,255);
		//$pdf->Cell(70, 10, 'Factura Nro.: '.$ventas_numero_factura_venta, 1,1,'C',1);
		$pdf->Cell(70, 4, '', 0,1,'C',1);
		$cab_alto1=4;
		$mar_left1=1;
		$cab_col2=105;
		
		$pdf->Cell($mar_left1,$cab_alto1, ' ', 0,0);
		$pdf->Cell($cab_col2,$cab_alto1, 'Fecha........: '.$ventas_fecha_venta, 0,0);
		$pdf->Cell($cab_col2,$cab_alto1, 'Fecha........: '.$ventas_fecha_venta, 0,1);
		$pdf->Cell($mar_left1,$cab_alto1, ' ', 0,0);
		
		$pdf->Cell($cab_col2,$cab_alto1, 'Cliente......: '.$clientes_nombre." ".$clientes_apellido,0,0);
		$pdf->Cell($cab_col2,$cab_alto1, 'Cliente......: '.$clientes_nombre." ".$clientes_apellido,0,1);
		$pdf->Cell($mar_left1,$cab_alto1, ' ', 0,0);
		
		$pdf->Cell($cab_col2,$cab_alto1, 'Direccion....: '.$clientes_direccion, 0,0);
  		$pdf->Cell($cab_col2,$cab_alto1, 'Direccion....: '.$clientes_direccion, 0,1);
		$pdf->Cell($mar_left1,$cab_alto1, ' ', 0,0);
		
		$pdf->Cell($cab_col2,$cab_alto1, 'Telefono.....: '.$clientes_telefono,0,0);
		$pdf->Cell($cab_col2,$cab_alto1, 'Telefono.....: '.$clientes_telefono,0,1);
		$pdf->Cell($mar_left1,$cab_alto1, ' ', 0,0);
		
		$pdf->Cell($cab_col2,$cab_alto1, 'Cedula/Ruc...: '.$clientes_cedula, 0,0);
		$pdf->Cell($cab_col2,$cab_alto1, 'Cedula/Ruc...: '.$clientes_cedula,0,1);

        //$pdf->Cell(150,10, 'Telefono :: '.$ventas_fecha_venta, 0,1);
	
	//	$pdf->SetFillColor(232,232,232);
		$alto_cab2=4;
		
		$pdf->Cell($mar_left1,$alto_cab2, ' ', 0,0);
		$pdf->Cell(88,$alto_cab2, '_______________________________________________',0,0,'L',1);
     	$pdf->Cell(14,$alto_cab2, '     ',0,0,'C',0);
		$pdf->Cell(88,$alto_cab2, '_______________________________________________',0,1,'L',1);
     	
		$pdf->Cell($mar_left1,$alto_cab2, ' ', 0,0);
		
	    $pdf->Cell(12,$alto_cab2, 'Cant.',0,0,'C',1);
        $pdf->Cell(36,$alto_cab2, 'Detalles',0,0,'C',1);
		$pdf->Cell(17,$alto_cab2, 'Unidad',0,0,'C',1);         
		$pdf->Cell(14,$alto_cab2, 'Precio',0,0,'C',1);
        $pdf->Cell(14,$alto_cab2, 'Precio',0,0,'C',1); 	
		//$pdf->Cell(1,1, '',1,0,'C',1); 	
		
		$pdf->SetFillColor(255,255,255);
		$pdf->Cell(10,$alto_cab2, '     ',0,0,'C',1);				
		$pdf->Cell(12,$alto_cab2, 'Cant.',0,0,'C',1);
        $pdf->Cell(36,$alto_cab2, 'Detalles',0,0,'C',1);
		$pdf->Cell(17,$alto_cab2, 'Unidad',0,0,'C',1);         
		$pdf->Cell(14,$alto_cab2, 'Precio',0,0,'C',1);
        $pdf->Cell(14,$alto_cab2, 'Precio',0,1,'C',1); 	

		$pdf->Cell($mar_left1,$alto_cab2-3, ' ', 0,0);
		
		$pdf->Cell(65,$alto_cab2-1, '   ',0,0,'L',1);
		$pdf->Cell(14,$alto_cab2-1, 'Unico',0,0,0,'L',1);
        $pdf->Cell(12,$alto_cab2-1, ' Total',0,0,'L',1); 	
		$pdf->Cell(1,1, '',0,0,'C',1); 	
		
		$pdf->SetFillColor(255,255,255);
		$pdf->Cell(77,$alto_cab2-3, '   ',0,0,'L',1);				
		$pdf->Cell(14,$alto_cab2-1, 'Unico',0,0,'L',1);
        $pdf->Cell(12,$alto_cab2-1, ' Total',0,1,'L',1); 	
		
    	
		$pdf->Cell($mar_left1,$alto_cab2-2, ' ', 0,0);
	//	$pdf->Cell(64,$alto_cab2-2, '________________________________________________',0,0,'L',1);
    // 	$pdf->Cell(7,$alto_cab2-2, '     ',0,0,'C',0);
	//	$pdf->Cell(26,$alto_cab2-2, '________________________________________________',0,1,'L',1);
		
		$pdf->Cell(88,$alto_cab2, '_______________________________________________',0,0,'L',1);
     	$pdf->Cell(14,$alto_cab2, '     ',0,0,'C',0);
		$pdf->Cell(88,$alto_cab2, '_______________________________________________',0,1,'L',1);
     	
 //	    $pdf->SetFont('Arial', 'B', 6);
	    $pdf->SetFont('Arial', 'B', 9);
	    	
		$sql2="SELECT 
			detalle_ventas.`id_venta` AS detalle_ventas_id_venta,
			detalle_ventas.`id_servicio` AS detalle_ventas_id_servicio,
			detalle_ventas.`cantidad` AS detalle_ventas_cantidad,
			detalle_ventas.`v_unitario` AS detalle_ventas_v_unitario,
			detalle_ventas.`v_total` AS detalle_ventas_v_total,
			servicios.`codigo` AS servicios_codigo,
			servicios.`nombre` AS servicios_nombre,
			servicios.`id_unidad` AS servicios_id_unidad,
			unidades.`nombre` AS unidades_nombre,
			servicios.`iva` AS servicios_iva
		from `detalle_ventas` detalle_ventas INNER JOIN `ventas` ventas
			ON detalle_ventas.`id_venta` = ventas.`id_venta`
			INNER JOIN `servicios` servicios
			ON detalle_ventas.`id_servicio` = servicios.`id_servicio`
			INNER JOIN `unidades` unidades
			ON servicios.`id_unidad` = unidades.`id_unidad`
			
		WHERE detalle_ventas.`id_venta`='".$ventas_id_venta."'; ";
				
		$result2=mysql_query($sql2);
        $detalle_ventas_id_ventas= array();
		$detalle_ventas_id_servicio= array();
		$servicios_iva= array();		
		$detalle_ventas_cantidad= array();
        $detalle_ventas_v_unitario= array();
		$detalle_ventas_descuento= array();
        $detalle_ventas_v_total= array();
		$servicios_codigo= array();
		$servicios_nombre= array();
		
		$totalSubVenta = 0;
		$totalDscto = 0;
		$totalTarifa0 = 0;		
		$totalTarifa12 = 0;
		$totalIva = 0;
		$totalVenta = 0;
        $alto2=3;   	
		$b=0;
		$lin=0;
        $num_filas_detalle_ventas = mysql_num_rows($result2); // obtenemos el número de filas
		//echo $num_filas_detalle_pedido;
		while($row2=mysql_fetch_array($result2))//permite ir de fila en fila de la tabla
        {
            $detalle_ventas_id_venta = $row2['detalle_ventas_id_venta'];
            $detalle_ventas_id_servicio = $row2['detalle_ventas_id_servicio'];
			$servicios_iva = $row2['servicios_iva'];
			
			$unidades_nombre = $row2['unidades_nombre'];
			
			$detalle_ventas_cantidad = $row2['detalle_ventas_cantidad'];
			$detalle_ventas_descuento=0;
			$servicios_codigo = $row2['servicios_codigo'];
            $servicios_nombre = $row2['servicios_nombre'];
			$detalle_ventas_v_unitario = $row2['detalle_ventas_v_unitario'];
           // $detalle_ventas_descuento = $row2['detalle_ventas_descuento'];
		    $detalle_ventas_v_total = $row2['detalle_ventas_v_total'];         
		    $totalSubVenta = $totalSubVenta + $row2['detalle_ventas_v_total'];
			$totalDscto = $totalDscto + $detalle_ventas_descuento;
		
			if ( $servicios_iva =="Si" )
			{
				$totalTarifa12 = $totalTarifa12 + $row2['detalle_ventas_v_total'];
			}
			else
			{
				$totalTarifa0 = $totalTarifa0 + $row2['detalle_ventas_v_total'];
			}

			$total_iva=0;
			$total=0;
			if($servicios_iva == "Si"){
				// SACA LOS IMPUESTOS ACTUAL
				$sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
				$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error"><p>Error en impuestos: '.mysql_error().' </p></div>  ');;
				$iva=0;
				$impuestos_id_plan_cuenta = 0;
				while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
				{
					$iva=$rowIva1['iva'];
					$txtIdIva=$rowIva1['id_iva'];
					//$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
				}
				$total_iva = ($detalle_ventas_cantidad*$detalle_ventas_v_unitario * $iva)/100;
				$total = $detalle_ventas_cantidad*$detalle_ventas_v_unitario + $total_iva;
			}
			$totalIva = $totalIva + $total_iva;
			$totalVenta = $totalVenta + $row2['detalle_ventas_v_total']+ $totalIva;
           		
			//$pdf->Cell(15,10, number_format($detalle_ventas_cantidad, 2, '.', ' '),1,0,'R');
			$pdf->Cell($mar_left1,$alto2, ' ', 0,0);
		
		/* $pdf->Cell(12,$alto_cab2, 'Cant.',0,0,'C',1);
        $pdf->Cell(36,$alto_cab2, 'Detalles',0,0,'C',1);
		$pdf->Cell(17,$alto_cab2, 'Unidad',0,0,'C',1);         
		$pdf->Cell(14,$alto_cab2, 'Precio',0,0,'C',1);
        $pdf->Cell(14,$alto_cab2, 'Precio',0,1,'C',1); 	
 */
		
		
			$pdf->Cell(10,$alto2, $detalle_ventas_cantidad,0,0,'R');
			$pdf->Cell(36,$alto2, substr($servicios_nombre,0,40),0,0,'L');
			$pdf->Cell(16,$alto2, $unidades_nombre,0,0,'C');         
			$pdf->Cell(14,$alto2, number_format($detalle_ventas_v_unitario,2,'.',' '),0,0,'R');
			$pdf->Cell(14,$alto2, number_format($detalle_ventas_v_total,2,'.',''),0,0,'R'); 
			$pdf->Cell(3,$alto2,'$',0,0,'L'); 
			
			$pdf->SetFillColor(255,255,255);
			$pdf->Cell(8,$alto2, '     ',0,0,'C',1);				
			$pdf->Cell(10,$alto2, $detalle_ventas_cantidad,0,0,'R');
			$pdf->Cell(36,$alto2, $servicios_nombre,0,0,'L');
			$pdf->Cell(16,$alto2, $unidades_nombre,0,0,'C');         
			$pdf->Cell(14,$alto2, number_format($detalle_ventas_v_unitario,2,'.',''),0,0,'R');
			$pdf->Cell(14,$alto2, number_format($detalle_ventas_v_total,2,'.',''),0,0,'R'); 
			$pdf->Cell(1,$alto2,'$',0,1,'L');
			$lin+lin+1;
		}
		
		for ($i=$lin; $i<4;$i++)
		{
		    $pdf->Cell($mar_left1,$alto2, '-', 0,1);
		    $pdf->Cell($mar_left1,$alto2, ' ', 0,1);
		    
		}
	//	$pdf->Cell($mar_left1,$alto2, '--', 0,1);
		
		$col2=49;	
		$col3=20;
		$col4=20;
		$col5=60;
		$pdf->Cell($mar_left1,$alto2, ' ', 0,0);
		
		$pdf->Cell($col2, $alto2, ' ', 0,0); 
		$pdf->Cell($col3, $alto2, 'Neto: ',0,0);
		$pdf->Cell($col4, $alto2, number_format($totalSubVenta, 2, '.', ' '), 0,0,'R');	
	 	$pdf->Cell(1,$alto2,'$',0,0,'L');
		$pdf->Cell($col5, $alto2, ' ', 0,0); 
		
		$pdf->Cell($col3, $alto2, 'Neto: ',0,0);
		$pdf->Cell($col4, $alto2, number_format($totalSubVenta, 2, '.', ' '), 0,0,'R');	
		$pdf->Cell(1,$alto2,'$',0,1,'L');

	/* 	$pdf->Cell($mar_left1,$alto2, ' ', 0,0);
	 	
		$pdf->Cell($col2, $alto2, ' ', 0,0); 
		$pdf->Cell($col3, $alto2, 'Descuento:', 1,0);
        $pdf->Cell($col4, $alto2, number_format($totalDscto, 2, '.', ' '), 1,0,'R');
		$pdf->Cell($col5, $alto2, ' ', 0,0); 
		$pdf->Cell($col3, $alto2, 'Descuento:', 1,0);
        $pdf->Cell($col4, $alto2, number_format($totalDscto, 2, '.', ' '), 1,1,'R');
	 */	
		$pdf->Cell($mar_left1,$alto2, ' ', 0,0);
		
		$pdf->Cell($col2, $alto2, ' ', 0,0); 
		$pdf->Cell($col3, $alto2, 'Iva 0', 0,0);		
		$pdf->Cell($col4, $alto2, number_format($totalTarifa0, 2, '.', ' '), 0,0,'R');
		$pdf->Cell(1,$alto2,'$',0,0,'L');
		
		$pdf->Cell($col5, $alto2, ' ', 0,0); 
		$pdf->Cell($col3, $alto2, 'Iva 0', 0,0);		
		$pdf->Cell($col4, $alto2, number_format($totalTarifa0, 2, '.', ' '), 0,0,'R');
		$pdf->Cell(1,$alto2,'$',0,1,'L');

		$pdf->Cell($mar_left1,$alto2, ' ', 0,0);
		
	//	$pdf->Cell($col2, $alto2, ' ', 0,0); 
	//	$pdf->Cell($col3, $alto2, 'Iva 12%', 0,0);
	//	$pdf->Cell($col4, $alto2, number_format($totalTarifa12, 2, '.', ' '), 0,0,'R');
	//	$pdf->Cell(1,$alto2,'$',0,0,'L');
		
	//	$pdf->Cell($col5, $alto2, ' ', 0,0); 
	//	$pdf->Cell($col3, $alto2, 'Iva 12', 0,0);
	//	$pdf->Cell($col4, $alto2, number_format($totalTarifa12, 2, '.', ' '), 0,0,'R');
	//	$pdf->Cell(1,$alto2,'$',0,1,'L');
		
	//	$pdf->Cell($mar_left1,$alto2, ' ', 0,0);
		
		$pdf->Cell($col2, $alto2, ' ', 0,0); 
		$pdf->Cell($col3, $alto2,'Iva 12', 0,0);
    	$pdf->Cell($col4, $alto2, number_format($totalIva, 2, '.', ' '), 0,0,'R');
		$pdf->Cell(1,$alto2,'$',0,0,'L');
		
		$pdf->Cell($col5, $alto2, ' ', 0,0); 
		$pdf->Cell($col3, $alto2,'Iva 12%', 0,0);
    	$pdf->Cell($col4, $alto2, number_format($totalIva, 2, '.', ' '), 0,0,'R');
		$pdf->Cell(1,$alto2,'$',0,1,'L'); 
		
		$pdf->Cell($mar_left1,$alto2, ' ', 0,0);
		
		
		
//		if($otros>0){
//		$pdf->Cell($col2, $alto2, ' ', 0,0); 
//		$pdf->Cell($col3, $alto2,'Otros', 0,0);
 //   	$pdf->Cell($col4, $alto2, number_format($otros, 2, '.', ' '), 0,0,'R');
//		$pdf->Cell(1,$alto2,'$',0,0,'L');
//		
//		$pdf->Cell($col5, $alto2, ' ', 0,0); 
//		$pdf->Cell($col3, $alto2,'Otros', 0,0);
//    	$pdf->Cell($col4, $alto2, number_format($otros, 2, '.', ' '), 0,0,'R');
//		$pdf->Cell(1,$alto2,'$',0,1,'L'); 
		
//		$pdf->Cell($mar_left1,$alto2, ' ', 0,0);
		
//		}
		
		
		
		
		
		
		$pdf->Cell($col2, $alto2, ' ', 0,0); 
		$pdf->Cell($col3, $alto2,'Total:', 0,0);
		$pdf->Cell($col4, $alto2, number_format($totalIva+$totalSubVenta+$otros, 2, '.', ' '), 0,0,'R');
		$pdf->Cell(1,$alto2,'$',0,0,'L');
		$pdf->Cell($col5, $alto2, ' ', 0,0); 
		$pdf->Cell($col3, $alto2,'Total:', 0,0);
		$pdf->Cell($col4, $alto2, number_format($totalIva+$totalSubVenta+$otros, 2, '.', ' '), 0,0,'R');
		$pdf->Cell(1,$alto2,'$',0,1,'L'); 
		
		
		$pdf->Cell($mar_left1,$alto2, ' ', 0,1);
		$pdf->Cell($mar_left1,$alto2, ' ', 0,1);
		
		$pdf->Cell($mar_left1,$alto2, ' ', 0,0);
		$pdf->Cell($col2+10, $alto2,'________________________',0,0,'L');
		$pdf->Cell(5, $alto2,'     ',0,0,'L');
		$pdf->Cell($col4+10, $alto2,'________________________',0,0,'R');
		
		$pdf->Cell(5, $alto2,'     ',0,0,'L');
		
		$pdf->Cell($mar_left1,$alto2, ' ', 0,0);
		$pdf->Cell($col2+10, $alto2,'________________________',0,0,'L');
		$pdf->Cell(5, $alto2,'     ',0,0,'L');
		
		$pdf->Cell($col4+10, $alto2,'________________________',0,1,'R');
		
	//	
		
		$pdf->Cell($mar_left1+5,$alto2, ' ', 0,0);
		$pdf->Cell($col2+5, $alto2,'  Recibi Conforme',0,0,'L');
		$pdf->Cell($col4+5, $alto2,'Firma Autorizada',0,0,'L');
		$pdf->Cell(17, $alto2,'     ',0,0,'L');
		
		$pdf->Cell($mar_left1,$alto2, ' ', 0,0);
		$pdf->Cell($col2+5, $alto2,'  Recibi Conforme',0,0,'L');
		$pdf->Cell($col4+5, $alto2,'Firma Autorizada',0,1,'L');
		
		
	//	$pdf->Cell(1,$alto2,'$',0,0,'L'); 
		
	//	$pdf->Cell($col5, $alto2, ' ', 0,0); 
	//	$pdf->Cell($col2, $alto2,'Abono + Interes - Se debe Pagar:', 0,0,'L');
	//	$pdf->Cell($col4, $alto2, number_format($totalVenta, 2, '.', ' '), 0,0,'R');
	//	$pdf->Cell(1,$alto2,'$',0,1,'L'); 
		
		
	//	$pdf->Cell($mar_left1,$alto2, ' ', 0,0);
	//	$pdf->Cell($col2, $alto2,'Abono + Interes - Se debe Pagar:', 0,0,'L');
	//	$pdf->Cell($col4, $alto2, number_format($totalVenta, 2, '.', ' '), 0,0,'R');
	//	$pdf->Cell(1,$alto2,'$',0,0,'L'); 
		
	//	$pdf->Cell($col5, $alto2, ' ', 0,0); 
	//	$pdf->Cell($col2, $alto2,'Abono + Interes - Se debe Pagar:', 0,0,'L');
	//	$pdf->Cell($col4, $alto2, number_format($totalVenta, 2, '.', ' '), 0,0,'R');
	//	$pdf->Cell(1,$alto2,'$',0,1,'L'); 
		
		
	//	$pdf->Cell($mar_left1,$alto2, ' ', 0,0);
	//	$pdf->Cell($col2+21, $alto2,'Pagado en efectivo:', 0,0,'L');
	//	$pdf->Cell($col5, $alto2, ' ', 0,0); 
	//	$pdf->Cell($col2+20, $alto2,'Pagado en efectivo:', 0,1,'L');
		
		
		
	}
//	$pdf->Output();
	$pdf->Output();
?>