<?php
   session_start();
	require_once('../conexion.php');
    require "./code128.php";
    
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_empresa_imagen = $_SESSION["sesion_empresa_imagen"];
    $id_venta = $_GET["txtComprobanteNumero"];
    
    function ceros($valor){
               $s='';
        for($i=1;$i<=9-strlen($valor);$i++)
                $s.="0";
        return $s.$valor;
    }
                        

    $resultVentaC=mysql_query( "select *,establecimientos.codigo as est,emision.codigo as emi from ventas,establecimientos,emision 
    where id_venta='".$id_venta."' and establecimientos.id=ventas.codigo_pun and emision.id=ventas.codigo_lug");
	$OventaC=mysql_fetch_array($resultVentaC);
    $OventaC['direccion'];
    
    $codDoc = $OventaC['tipo_documento'];
    

    $resultDetallesVentaC=mysql_query( "select COUNT(*) as detalles from  detalle_ventas where id_venta='".$id_venta."' ");
    $ODetallesVentaC=mysql_fetch_array($resultDetallesVentaC);
    
    $numDetalles=$ODetallesVentaC['detalles'];
    $altura = $numDetalles*7;

    $pdf = new PDF_Code128('P','mm',array(80,258+$altura));
    $pdf->SetMargins(4,10,4);
    $pdf->AddPage();
    
    $imagenEmpresa='';
    $selectIm="SELECT imagen from empresa where id_empresa='".$sesion_id_empresa."'";
$resultIm = mysql_query($selectIm) or die(mysql_error());
 while($rowIm = mysql_fetch_array($resultIm)) {
     $imagenEmpresa =$rowIm['imagen'];
 }
if(trim($imagenEmpresa) !=''){
    $imagenEmpresa = "../sql/archivos/".$imagenEmpresa;
    if(file_exists($imagenEmpresa)){
    $pdf->setY(12);
    $pdf->Image($imagenEmpresa, 5, 0, 70 , 30, '', '');
    $pdf->setY(30);
    // $pdf->Ln();
    $pdf->setX(5);
}
}

    $resultEmpresa=mysql_query( "select * from empresa where id_empresa='".$sesion_id_empresa."' ");
	$Oempresa=mysql_fetch_array($resultEmpresa);
    
    # Encabezado y datos de la empresa #
    $pdf->SetFont('Arial','B',10);
    $pdf->SetTextColor(0,0,0);
    $pdf->MultiCell(0,5,utf8_decode(strtoupper($Oempresa['nombre'])),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode(strtoupper($Oempresa['razonSocial'])),0,'C',false);
    
    $pdf->SetFont('Arial','',9);
    
    
    $pdf->MultiCell(0,5,utf8_decode("RUC:" .$Oempresa['ruc']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Dirección: " .$Oempresa['direccion']),0,'C',false);
    
    $pdf->MultiCell(0,5,utf8_decode("Teléfono:" .$Oempresa['telefono1']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Email:" .$Oempresa['email']),0,'C',false);
    $obc=($Oempresa['Ocontabilidad']==1)?"SI":"NO";
    $pdf->MultiCell(0,5,utf8_decode("Obligado a llevar contabilidad:" .$obc),0,'C',false); 
    
    if($Oempresa['leyenda']!=''){
        $pdf->MultiCell(0,5,utf8_decode($Oempresa['leyenda']),0,'C',false); 
    }
    if($Oempresa['leyenda2']!=''){
        if($Oempresa['leyenda2']=='1'){
		    $retencion='Agente de Retencion Resolución Nro. NAC-DNCRASC20-00000001 ';
		}else{
		      $retencion='';
		}
        $pdf->MultiCell(0,5,utf8_decode($retencion),0,'C',false); 
        
    }
    
                        
    
    $pdf->Ln(1);
    $pdf->Cell(0,5,utf8_decode("------------------------------------------------------"),0,0,'C');
    $pdf->Ln(5);

    

    $resultVenta=mysql_query( "select *,establecimientos.codigo as est,emision.codigo as emi, vendedor_id_tabla from ventas,establecimientos,emision 
    where id_venta='".$id_venta."' and establecimientos.id=ventas.codigo_pun and emision.id=ventas.codigo_lug");
	$Oventa=mysql_fetch_array($resultVenta);
    $pdf->MultiCell(0,5,utf8_decode("Dirección Est: " .$Oventa['direccion']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Fecha Emisión: ").date("d/m/Y h:i:s A", strtotime($Oventa['fecha_venta'])),0,'C',false);

    $pdf->SetFont('Arial','B',10);
    
    
    	if(  $codDoc=='100'){
    	    $tipoc= 'Nota de venta';
						
		}else{
		    $tipoc= 'Factura';
		}
    $pdf->MultiCell(0,5,utf8_decode($tipoc." #: ".strtoupper($Oventa['est']."-".$Oventa['emi']."-".ceros($Oventa['numero_factura_venta']))),0,'C',false);
    $pdf->SetFont('Arial','',9);

    $pdf->Ln(1);
    $pdf->Cell(0,5,utf8_decode("------------------------------------------------------"),0,0,'C');
    $pdf->Ln(5);
    
    $resultCliente=mysql_query( "select * from clientes where id_cliente='".$Oventa['id_cliente']."' ");
	$Ocliente=mysql_fetch_array($resultCliente);
    

    $pdf->MultiCell(0,5,utf8_decode("Cliente: ".$Ocliente['nombre']." ".$Ocliente['apellido']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("RUC/CI: ".$Ocliente['cedula']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Teléfono: ".$Ocliente['telefono']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Dirección: ".$Ocliente['direccion']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Email: ".$Ocliente['email']),0,'C',false);
     $pdf->Ln(1);
     
     $sqlVendedor = "SELECT `id_vendedor`, `codigo`, `nombre`, `apellidos`, `direccion`, `telefono`, `comision`, `id_empresa`, `caracter_identificacion`, `cedula`, `estado`, `email`, `tipo_vendedor` FROM `vendedores` WHERE id_vendedor='".$Oventa['vendedor_id_tabla']."'";
     $resultVendedor = mysql_query($sqlVendedor);
     $nombreVendedor="";
     while($rowVend = mysql_fetch_array($resultVendedor) ){
         $nombreVendedor= $rowVend['nombre'].' '.$rowVend['apellidos'];
     }
     if(trim($nombreVendedor)!=''){
          $pdf->MultiCell(0,5,utf8_decode("Vendedor: ".$nombreVendedor),0,'C',false);
    $pdf->Ln(1);
     }
    
    $pdf->Cell(0,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');
    $pdf->Ln(3);
    
    # Tabla de productos #
    $pdf->Cell(10,5,utf8_decode("Cant."),0,0,'C');
    $pdf->Cell(19,5,utf8_decode("Precio"),0,0,'C');
    $pdf->Cell(15,5,utf8_decode("Desc."),0,0,'C');
    $pdf->Cell(28,5,utf8_decode("Total"),0,0,'C');

    $pdf->Ln(3);
    $pdf->Cell(72,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');
    $pdf->Ln(5);



    /*----------  Detalles de la tabla  ----------*/
    
    $resultDetalleVenta=mysql_query( "select *,detalle_ventas.detalle as detalleVenta 
    from  detalle_ventas,productos where id_venta='".$id_venta."' and id_producto=id_servicio");
    
   $grilla=array();
    while($ODetalleVenta=mysql_fetch_array($resultDetalleVenta)){
        $campo = ($ODetalleVenta['iva']=='Si')?'* ':'';
        
        $pdf->SetFont('Arial','B',10);
        $pdf->MultiCell(0,4,$campo.utf8_decode($ODetalleVenta['producto']),0,'L',false);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(10,4,utf8_decode($ODetalleVenta['cantidad']),0,0,'C');
        $pdf->Cell(19,4,number_format($ODetalleVenta['v_unitario'],2),0,0,'C');
        $pdf->Cell(19,4,number_format($ODetalleVenta['descuento'],2),0,0,'C');
        $pdf->Cell(28,4,number_format($ODetalleVenta['v_total'],2),0,0,'C');
        $pdf->Ln(4);
        if($ODetalleVenta['detalleVenta']!=''){
            $pdf->MultiCell(0,4,utf8_decode($ODetalleVenta['detalleVenta']),0,'L',false);
            $pdf->Ln(7);
        }
       $grilla['producto'][] = $campo.utf8_decode($ODetalleVenta['producto']);
       $grilla['cantidad'][] = utf8_decode($ODetalleVenta['cantidad']) ;
       $grilla['v_unitario'][] = number_format($ODetalleVenta['v_unitario'],2) ;
       $grilla['descuento'][] = number_format($ODetalleVenta['descuento'],2) ;
       $grilla['v_total'][] = number_format($ODetalleVenta['v_total'],2) ;
       $grilla['detalleVenta'][] = utf8_decode($ODetalleVenta['detalleVenta']) ;
    }

    $pdf->Cell(72,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');

    $pdf->Ln(5);
    
    $suma_detalles_iva=0;
    $sqlDetalleV = "SELECT impuestos.id_iva, impuestos.codigo, impuestos.iva, detalle_ventas.`id_detalle_venta`, detalle_ventas.`idBodega`, detalle_ventas.`idBodegaInventario`, detalle_ventas.`cantidad`, detalle_ventas.`estado`, detalle_ventas.`v_unitario`, detalle_ventas.`descuento`, SUM(detalle_ventas.`v_total`) AS base_imponible, detalle_ventas.`id_venta`, detalle_ventas.`id_servicio`, detalle_ventas.`detalle`, detalle_ventas.`id_kardex`, detalle_ventas.`tipo_venta`, detalle_ventas.`id_empresa`,  detalle_ventas.`tarifa_iva`, SUM(detalle_ventas.`total_iva`) as suma_iva FROM `detalle_ventas` INNER JOIN impuestos ON impuestos.id_iva = detalle_ventas.tarifa_iva WHERE detalle_ventas.id_venta = '".$id_venta."'  GROUP BY impuestos.id_iva ";
				$resultDetVenta = mysql_query( $sqlDetalleV );
				   $array_ivas= array ();
	$listado_subtotales=array();
	$cantidad_subtotales=0;
	while($rowDetVent = mysql_fetch_array($resultDetVenta) ){
		$subT12 = $rowDetVent['base_imponible'];
		$pdf->Cell(18,5,utf8_decode(""),0,0,'C');
        $pdf->Cell(22,5,utf8_decode("SUBTOTAL ".$rowDetVent['iva'].' %'),0,0,'R');
        $pdf->Cell(28, 5, number_format($subT12, 2), 0, 0, 'R');
        
        $listado_subtotales['campo'][] =utf8_decode("SUBTOTAL ".$rowDetVent['iva'].' %');
        $listado_subtotales['cantidad'][] =number_format($subT12, 2);
        $listado_subtotales['tarifa_iva'][] = $rowDetVent['tarifa_iva'] ;  
        
        $pdf->Ln(5);
        $suma_detalles_iva += floatval($rowDetVent['suma_iva']);
        $clave = $rowDetVent['tarifa_iva'] ;  
                    if (array_key_exists($clave, $array_ivas)) {
                        $array_ivas[$clave][0]= $rowDetVent['iva'];
                        $array_ivas[$clave][1]  += floatval($rowDetVent['suma_iva']);
                    } else {
                        $array_ivas[$clave][0]= $rowDetVent['iva'];
                        $array_ivas[$clave][1] = floatval($rowDetVent['suma_iva']);
                    }
       $cantidad_subtotales++;             
				}
    $resultImpuestos=mysql_query( "select * from impuestos where id_empresa='".$sesion_id_empresa."' ");
	$Oimpuestos=mysql_fetch_array($resultImpuestos);    
    // echo "select * from impuestos where id_empresa='".$sesion_id_empresa."' ";

  
	foreach ($array_ivas as $key => $value) {
            //   echo "Key: $key, Value: $value\n";
            if($value[1]>0 ){
                  $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(22,5,utf8_decode("IVA ".$value[0]." %: "),0,0,'R');
$pdf->Cell(28,5,number_format($value[1],2),0,0,'R');
 $pdf->Ln(5);
            }
            
            } 
            
   

    $pdf->Cell(72,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');

    $pdf->Ln(5);

    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(22,5,utf8_decode("SUBTOTAL"),0,0,'R');
    $pdf->Cell(28,5,utf8_decode($Oventa['sub_total']),0,0,'R');

    $pdf->Ln(5);
    
    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(22,5,utf8_decode("DESCUENTO"),0,0,'R');
    $pdf->Cell(28,5,utf8_decode($Oventa['descuento']),0,0,'R');

    $pdf->Ln(5);

    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(22,5,utf8_decode("PROPINA"),0,0,'R');
    $pdf->Cell(28,5,utf8_decode($Oventa['propina']),0,0,'R');
    $pdf->Ln(5);

      $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(22,5,utf8_decode("TOTAL"),0,0,'R');
    $pdf->Cell(28,5,$Oventa['total'],0,0,'R');
 $pdf->Ln(5);   

     if($Oventa['entregado']>0  ){
   $pdf->Cell(72,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');

    $pdf->Ln(5);              
        $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
        $pdf->Cell(22,5,utf8_decode("ENTREGADO"),0,0,'R');
        $pdf->Cell(28,5,$Oventa['entregado'],0,0,'R');
        $pdf->Ln(5);
                            
        $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
        $pdf->Cell(22,5,utf8_decode("CAMBIO"),0,0,'R');
        $pdf->Cell(28,5,$Oventa['cambio'],0,0,'R');
        $pdf->Ln(5);
        
            }

    if($Oempresa['leyenda3']!=''){
        $pdf->MultiCell(0,5,utf8_decode($Oempresa['leyenda3']),0,'C',false);
    }
    
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(0,7,utf8_decode("Gracias por su compra"),'',0,'C');
   $espacioCodigo=0;
if($Oventa['ClaveAcceso']!=''){
    $pdf->Ln(9);
     # Codigo de barras #
    $pdf->Code128(5,$pdf->GetY(),$Oventa['ClaveAcceso'],70,10);
    $pdf->SetXY(0,$pdf->GetY()+10);
    $pdf->SetFont('Arial','',7);
    $pdf->MultiCell(0,5,utf8_decode($Oventa['ClaveAcceso']),0,'C',false);
    $espacioCodigo=5;
}
  
  $pdf->Ln();
   $alturaDocumento = $pdf->GetY()+20.1-$espacioCodigo;
   
    //--------------------------------COMIENZA-----------------------
     $pdf2 = new PDF_Code128('P','mm',array(80,$alturaDocumento));
    $pdf2->SetMargins(4,10,4);
    $pdf2->AddPage();
   
    if(trim($imagenEmpresa) !=''){
        if(file_exists($imagenEmpresa)){
        $pdf2->setY(12);
        $pdf2->Image($imagenEmpresa, 5, 0, 70 , 30, '', '');
        // $pdf2->Ln();
        $pdf2->setY(30);
        $pdf2->setX(5);
        }
    }

    $pdf2->SetFont('Arial','B',10);
    $pdf2->SetTextColor(0,0,0);
    $pdf2->MultiCell(0,5,utf8_decode(strtoupper($Oempresa['nombre'])),0,'C',false);
    $pdf2->MultiCell(0,5,utf8_decode(strtoupper($Oempresa['razonSocial'])),0,'C',false);
    
    $pdf2->SetFont('Arial','',9);
    
    
    $pdf2->MultiCell(0,5,utf8_decode("RUC:" .$Oempresa['ruc']),0,'C',false);
    $pdf2->MultiCell(0,5,utf8_decode("Dirección: " .$Oempresa['direccion']),0,'C',false);
    
    $pdf2->MultiCell(0,5,utf8_decode("Teléfono:" .$Oempresa['telefono1']),0,'C',false);
    $pdf2->MultiCell(0,5,utf8_decode("Email:" .$Oempresa['email']),0,'C',false);
    $obc=($Oempresa['Ocontabilidad']==1)?"SI":"NO";
    $pdf2->MultiCell(0,5,utf8_decode("Obligado a llevar contabilidad:" .$obc),0,'C',false); 
    
    if($Oempresa['leyenda']!=''){
        $pdf2->MultiCell(0,5,utf8_decode($Oempresa['leyenda']),0,'C',false); 
    }
    if($Oempresa['leyenda2']!=''){
        if($Oempresa['leyenda2']=='1'){
		    $retencion='Agente de Retencion Resolución Nro. NAC-DNCRASC20-00000001 ';
		}else{
		      $retencion='';
		}
        $pdf2->MultiCell(0,5,utf8_decode($retencion),0,'C',false); 
        
    }
    $pdf2->Ln(1);
    $pdf2->Cell(0,5,utf8_decode("------------------------------------------------------"),0,0,'C');
    $pdf2->Ln(5);
    $pdf2->MultiCell(0,5,utf8_decode("Dirección Est: " .$Oventa['direccion']),0,'C',false);
    $pdf2->MultiCell(0,5,utf8_decode("Fecha Emisión: ").date("d/m/Y h:i:s A", strtotime($Oventa['fecha_venta'])),0,'C',false);
    $pdf2->SetFont('Arial','B',10);
    	if(  $codDoc=='100'){
    	    $tipoc= 'Nota de venta';
		}else{
		    $tipoc= 'Factura';
		}
    $pdf2->MultiCell(0,5,utf8_decode($tipoc." #: ".strtoupper($Oventa['est']."-".$Oventa['emi']."-".ceros($Oventa['numero_factura_venta']))),0,'C',false);
    $pdf2->SetFont('Arial','',9);
    $pdf2->Ln(1);
    $pdf2->Cell(0,5,utf8_decode("------------------------------------------------------"),0,0,'C');
    $pdf2->Ln(5);
    $pdf2->MultiCell(0,5,utf8_decode("Cliente: ".$Ocliente['nombre']." ".$Ocliente['apellido']),0,'C',false);
    $pdf2->MultiCell(0,5,utf8_decode("RUC/CI: ".$Ocliente['cedula']),0,'C',false);
    $pdf2->MultiCell(0,5,utf8_decode("Teléfono: ".$Ocliente['telefono']),0,'C',false);
    $pdf2->MultiCell(0,5,utf8_decode("Dirección: ".$Ocliente['direccion']),0,'C',false);
    $pdf2->MultiCell(0,5,utf8_decode("Email: ".$Ocliente['email']),0,'C',false);
     $pdf2->Ln(1);
  
     if(trim($nombreVendedor)!=''){
          $pdf2->MultiCell(0,5,utf8_decode("Vendedor: ".$nombreVendedor),0,'C',false);
    $pdf2->Ln(1);
     }
    
    $pdf2->Cell(0,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');
    $pdf2->Ln(3);
    
    # Tabla de productos #
    $pdf2->Cell(10,5,utf8_decode("Cant."),0,0,'C');
    $pdf2->Cell(19,5,utf8_decode("Precio"),0,0,'C');
    $pdf2->Cell(15,5,utf8_decode("Desc."),0,0,'C');
    $pdf2->Cell(28,5,utf8_decode("Total"),0,0,'C');

    $pdf2->Ln(3);
    $pdf2->Cell(72,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');
    $pdf2->Ln(5);

  for($t1=0; $t1 < $numDetalles; $t1++){
        $pdf2->SetFont('Arial','B',10);
        $pdf2->MultiCell(0,4,$grilla['producto'][$t1],0,'L',false);
        $pdf2->SetFont('Arial','',9);
        $pdf2->Cell(10,4, $grilla['cantidad'][$t1],0,0,'C');
        $pdf2->Cell(19,4, $grilla['v_unitario'][$t1] ,0,0,'C');
        $pdf2->Cell(19,4, $grilla['descuento'][$t1] ,0,0,'C');
        $pdf2->Cell(28,4, $grilla['v_total'][$t1] ,0,0,'C');
        $pdf2->Ln(4);
        if($grilla['detalleVenta'][$t1] !=''){
            $pdf2->MultiCell(0,4, $grilla['detalleVenta'][$t1] ,0,'L',false);
            $pdf2->Ln(7);
        }
  }
   
    $pdf2->Cell(72,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');
    $pdf2->Ln(5);
    $suma_detalles_iva=0;
    
    for($t2=0; $t2<$cantidad_subtotales ;$t2++){
    	$pdf2->Cell(18,5,utf8_decode(""),0,0,'C');
        $pdf2->Cell(22,5, $listado_subtotales['campo'][$t2] ,0,0,'R');
        $pdf2->Cell(28, 5, $listado_subtotales['cantidad'][$t2], 0, 0, 'R');
        $pdf2->Ln(5);
    }

	foreach ($array_ivas as $key => $value) {
            if($value[1]>0 ){
                  $pdf2->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf2->Cell(22,5,utf8_decode("IVA ".$value[0]." %: "),0,0,'R');
$pdf2->Cell(28,5,number_format($value[1],2),0,0,'R');
 $pdf2->Ln(5);
            }
            
            } 
    $pdf2->Cell(72,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');

    $pdf2->Ln(5);

    $pdf2->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf2->Cell(22,5,utf8_decode("SUBTOTAL"),0,0,'R');
    $pdf2->Cell(28,5,utf8_decode($Oventa['sub_total']),0,0,'R');

    $pdf2->Ln(5);
    
    $pdf2->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf2->Cell(22,5,utf8_decode("DESCUENTO"),0,0,'R');
    $pdf2->Cell(28,5,utf8_decode($Oventa['descuento']),0,0,'R');

    $pdf2->Ln(5);

    $pdf2->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf2->Cell(22,5,utf8_decode("PROPINA"),0,0,'R');
    $pdf2->Cell(28,5,utf8_decode($Oventa['propina']),0,0,'R');

    $pdf2->Ln(5);
     $pdf2->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf2->Cell(22,5,utf8_decode("TOTAL"),0,0,'R');
    $pdf2->Cell(28,5,$Oventa['total'],0,0,'R');
    $pdf2->Ln(5);
     if($Oventa['entregado']>0 ){
                
         $pdf2->Cell(72,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');
         $pdf2->Ln(5);
        $pdf2->Cell(18,5,utf8_decode(""),0,0,'C');
        $pdf2->Cell(22,5,utf8_decode("ENTREGADO"),0,0,'R');
        $pdf2->Cell(28,5,$Oventa['entregado'],0,0,'R');
        $pdf2->Ln(5);
                            
        $pdf2->Cell(18,5,utf8_decode(""),0,0,'C');
        $pdf2->Cell(22,5,utf8_decode("CAMBIO"),0,0,'R');
        $pdf2->Cell(28,5,$Oventa['cambio'],0,0,'R');
        $pdf2->Ln(5);
        
            }
            
 

    if($Oempresa['leyenda3']!=''){
        $pdf2->MultiCell(0,5,utf8_decode($Oempresa['leyenda3']),0,'C',false);
    }
    
    $pdf2->SetFont('Arial','B',9);
    $pdf2->Cell(0,7,utf8_decode("Gracias por su compra"),'',0,'C');

    
if($Oventa['ClaveAcceso']!=''){
    $pdf2->Ln(9);
     # Codigo de barras #
    $pdf2->Code128(5,$pdf2->GetY(),$Oventa['ClaveAcceso'],70,10);
    $pdf2->SetXY(0,$pdf2->GetY()+10);
    $pdf2->SetFont('Arial','',7);
    $pdf2->MultiCell(0,5,utf8_decode($Oventa['ClaveAcceso']),0,'C',false);
}

    $pdf2->Output("I","Ticket_Nro_1.pdf",true);