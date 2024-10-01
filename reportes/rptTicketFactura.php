<?php
   session_start();
	# Incluyendo librerias necesarias #
	
	require_once('../conexion.php');
    require "./code128.php";

    // $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
     $sesion_id_empresa =41;
    $id_venta = $_GET["txtComprobanteNumero"];
    
    function ceros($valor){
               $s='';
        for($i=1;$i<=9-strlen($valor);$i++)
                $s.="0";
        return $s.$valor;
    }
    

         
      $resultDetalleVenta=mysql_query( "select *,detalle_ventas.detalle as detalleVenta 
    from  detalle_ventas,productos where id_venta='".$id_venta."' and id_producto=id_servicio");


      $resultEmpresa=mysql_query( "select * from empresa where id_empresa='".$sesion_id_empresa."' ");
	$Oempresa=mysql_fetch_array($resultEmpresa);
	
   

    
         $resultVenta=mysql_query( "select *,establecimientos.codigo as est,emision.codigo as emi from ventas,establecimientos,emision 
    where id_venta='".$id_venta."' and establecimientos.id=ventas.codigo_pun and emision.id=ventas.codigo_lug");
	$Oventa=mysql_fetch_array($resultVenta);


	
    $resultCliente=mysql_query( "select * from clientes where id_cliente='".$Oventa['id_cliente']."' ");
	$Ocliente=mysql_fetch_array($resultCliente);
	

	


    $pdf = new PDF_Code128('P','mm',array(80,$_GET['numLineas']));


   
        // $pdf = new PDF_Code128('P','mm',array(80,262));
    // 'letter'
    // $pdf = new PDF_Code128('P','mm','letter');
    $pdf->SetMargins(4,10,4);
    $pdf->SetAutoPageBreak(false,10);
    $pdf->AddPage();

    
    if(file_exists("../sql/archivos/".$Oempresa['imagen'])){
          $pdf->Image("../sql/archivos/".$Oempresa['imagen'], 15, 12, 50, 25, '', '');
          $pdf->SetY(40);
    }
    
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

    

   
    $pdf->MultiCell(0,5,utf8_decode("Dirección Est: " .$Oventa['direccion']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Fecha Emisión: ".date("d/m/Y", strtotime($Oventa['fecha_venta']))),0,'C',false);
    // $pdf->MultiCell(0,5,utf8_decode("Caja Nro: 1"),0,'C',false);
    // $pdf->MultiCell(0,5,utf8_decode("Cajero: Carlos Alfaro"),0,'C',false);
    $pdf->SetFont('Arial','B',10);
    $pdf->MultiCell(0,5,utf8_decode("Factura #: ".strtoupper($Oventa['est']."-".$Oventa['emi']."-".ceros($Oventa['numero_factura_venta']))),0,'C',false);
    $pdf->SetFont('Arial','',9);

    $pdf->Ln(1);
    $pdf->Cell(0,5,utf8_decode("------------------------------------------------------"),0,0,'C');
    $pdf->Ln(5);

    

    $pdf->MultiCell(0,5,utf8_decode("Cliente: ".$Ocliente['nombre']." ".$Ocliente['apellido']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("RUC/CI: ".$Ocliente['cedula']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Teléfono: ".$Ocliente['telefono']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Dirección: ".$Ocliente['direccion']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Email: ".$Ocliente['email']),0,'C',false);
    $pdf->Ln(1);
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

    while($ODetalleVenta=mysql_fetch_array($resultDetalleVenta)){
        $campo = ($ODetalleVenta['iva']=='Si')?'* ':'';
        
        $pdf->SetFont('Arial','B',10);


        $pdf->MultiCell(0,4, $campo.utf8_decode( trim($ODetalleVenta['producto'])),0,'L',false);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(10,4,utf8_decode($ODetalleVenta['cantidad']),0,0,'C');
        $pdf->Cell(19,4,utf8_decode($ODetalleVenta['v_unitario']),0,0,'C');
        $pdf->Cell(19,4,utf8_decode($ODetalleVenta['descuento']),0,0,'C');
        $pdf->Cell(28,4,utf8_decode($ODetalleVenta['v_total']),0,0,'C');
        $pdf->Ln(4);
        if($ODetalleVenta['detalleVenta']!=''){
            $pdf->MultiCell(0,4,utf8_decode($ODetalleVenta['detalleVenta']),0,'L',false);
            $pdf->Ln(7);
        }

        
    
    }
    
    /*----------  Fin Detalles de la tabla  ----------*/



    $pdf->Cell(72,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');

    $pdf->Ln(5);
    
    
    // $Oventa['fecha_venta']
    
    # Impuestos & totales #
    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(22,5,utf8_decode("SUBTOTAL 12%"),0,0,'R');
    $pdf->Cell(28,5,utf8_decode($Oventa['sub12']),0,0,'R');

    $pdf->Ln(5);
    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(22,5,utf8_decode("SUBTOTAL 0%"),0,0,'R');
    $pdf->Cell(28,5,utf8_decode($Oventa['sub0']),0,0,'R');

    $pdf->Ln(5);
    
    $resultImpuestos=mysql_query( "select * from impuestos where id_empresa='".$sesion_id_empresa."' ");
	$Oimpuestos=mysql_fetch_array($resultImpuestos);    
    // echo "select * from impuestos where id_empresa='".$sesion_id_empresa."' ";

    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(22,5,utf8_decode("IVA ".$Oimpuestos['iva']."%" ),0,0,'R');
    $pdf->Cell(28,5,utf8_decode($Oventa['sub12']*($Oimpuestos['iva']/100)),0,0,'R');

    $pdf->Ln(5);

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
    $pdf->Cell(28,5,utf8_decode($Oventa['total']),0,0,'R');

    $pdf->Ln(10);

    if($Oempresa['leyenda3']!=''){
        $pdf->MultiCell(0,5,utf8_decode($Oempresa['leyenda3']),0,'C',false);
    }
    
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(0,7,utf8_decode("Gracias por su compra"),'',0,'C');

    
if($Oventa['ClaveAcceso']!=''){
    $pdf->Ln(9);
     # Codigo de barras #
    $pdf->Code128(5,$pdf->GetY(),$Oventa['ClaveAcceso'],70,10);
    $pdf->SetXY(0,$pdf->GetY()+10);
    $pdf->SetFont('Arial','',7);

    $pdf->MultiCell(0,5,utf8_decode($Oventa['ClaveAcceso']),0,'C',false);
    
}
$pdf->Ln(1);
    //   	echo $pdf->GetY();
				// 		exit;

    //   	echo $pdf->GetY();
    
				// 		exit;
    # Nombre del archivo PDF #
    $pdf->Output("I","Factura_".$Ocliente['nombre']."_".$Ocliente['apellido'].strtoupper($Oventa['est']."-".$Oventa['emi']."-".ceros($Oventa['numero_factura_venta'])).".pdf",true);