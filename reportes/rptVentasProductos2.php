<?php
   session_start();
	# Incluyendo librerias necesarias #
	
	require_once('../conexion.php');
    require "./pdf_mc_table.php";
    
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];

    
    function ceros($valor){
               $s='';
            for($i=1;$i<=9-strlen($valor);$i++)
                $s.="0";
            return $s.$valor;
        }
    
    $fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
    $fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
    // $txtProveedor =  $_GET['txtProveedor'];
    
    $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
    $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
    $emision = $_GET['emision'];
    $txtClientes = $_GET['txtClientes']; 
    $ciudad= ($_GET['cbciudad']);


$tipoDoc= ($_GET['tipoDoc']);
    $pdf = new PDF_MC_Table('L','mm','A4');
    $pdf->SetMargins(4,10,4);
    $pdf->AddPage();
    
    
    $resultEmpresa=mysql_query( "select * from empresa where id_empresa='".$sesion_id_empresa."' ");
	$Oempresa=mysql_fetch_array($resultEmpresa);
    
    # Encabezado y datos de la empresa #
    $pdf->SetFont('Arial','B',10);
    $pdf->SetTextColor(0,0,0);
    $pdf->MultiCell(0,5,utf8_decode(strtoupper($Oempresa['nombre'])),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode(strtoupper($Oempresa['razonSocial'])),0,'C',false);
    $pdf->MultiCell(0,5,'REPORTE DE VENTAS POR PRODUCTO RESUMIDO',0,'C',false);
     $pdf->MultiCell(0,5,'Fecha Desde: '.$_GET['txtFechaDesde'],0,'C',false);
    $pdf->MultiCell(0,5,'Fecha Hasta: '.$_GET['txtFechaHasta'],0,'C',false);
    
    $borde = 1;
    $relleno = 1;
    
    $borde3 = 0;
    $relleno3 = 0;
    
    $borde2 = 1;
    $relleno2 = 1;
    
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetFont('Arial','',12);
    
  
	$sqlVentasSinDetalle="SELECT ventas.id_empresa, ventas.id_venta, ventas.numero_factura_venta, ventas.fecha_venta, detalle_ventas.id_detalle_venta, COUNT(detalle_ventas.id_detalle_venta) as total, ventas.fecha_anulacion from ventas LEFT JOIN detalle_ventas on detalle_ventas.id_venta = ventas.id_venta WHERE detalle_ventas.id_detalle_venta is null and ventas.id_empresa=$sesion_id_empresa  AND
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
	    
	       $estado= $_GET['estado']; 
    $autorizacion= $_GET['autorizacion'];
       if ($estado =='Pasivo'){
        $titulo ="ANULADAS";
    }else {
        $titulo='';
    }
    $txtUsuarios= $_GET['txtUsuarios'];
    
         
	$sql2 = "select SUM(detalle_ventas.cantidad) as total ,detalle_ventas.v_unitario, productos.id_producto, productos.producto, SUM(detalle_ventas.v_total) as sumatotal,
	        productos.iva as IVA, productos.codigo
	        from detalle_ventas 
	        INNER JOIN ventas ON ventas.id_venta = detalle_ventas.id_venta 
            INNER JOIN `clientes` clientes      ON ventas.`id_cliente` = clientes.`id_cliente` 
            INNER JOIN `emision` emision      ON ventas.`codigo_lug` = emision.`id` 
	        INNER JOIN productos ON productos.id_producto = detalle_ventas.id_servicio 
	        where ventas.id_empresa='".$sesion_id_empresa."'  
	        and DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d') >='".$_GET['txtFechaDesde']."' 
	        and DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d')<='".$_GET['txtFechaHasta']."' ";
    
    if ($emision !='0' && $emision!='GLOBAL'){
        $sql2.= " and emision.`id` ='".$emision."' "; 
    }
    if ($ciudad!=0  && $ciudad!='' ){
        $sql2.= "and clientes.`id_ciudad`='".$ciudad."' "; 
    }
    if ($txtClientes!='0' ){
		$sql2.= " and clientes.`id_cliente` = '".$txtClientes."' "; 
		}

    if ($txtUsuarios!='0'){
		$sql2.= " and ventas.`id_usuario`='".$txtUsuarios."' "; 
	}
	if ($tipoDoc!='0'){
		$sql2.= " and ventas.`tipo_documento`='".$tipoDoc."' "; 
	}
		if ($estado !='0'){
		$sql2.= " and ventas.`estado`='".$estado."' "; 
	}
		if ($autorizacion =='1'){
		$sql2.= " and ventas.`Autorizacion`!='' "; 
	}else if ($autorizacion =='2'){
	    $sql2.= " and ventas.`Autorizacion` IS NULL "; 
	}
	
	 $sql2 .= "  and ventas.id_venta NOT IN (".$listaSD.")  GROUP BY productos.codigo, productos.iva ";
// 	 if($sesion_id_empresa==41){
	    //   echo $sql2;
// 	 }
    $listado_anchos = array(105,20,35) ;
    $listado_alineacion = array('L','C','C');
       	$sqlImpuestos = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='".$sesion_id_empresa."'";
	$resulImpuestos = mysql_query($sqlImpuestos);
	$listadoImpuestos = array();
	$existe12= false;
	while($rowIv = mysql_fetch_array($resulImpuestos) ){
	    
	    $listadoImpuestos['id_iva'][]= $rowIv['id_iva'];
	    $listadoImpuestos['iva'][]= $rowIv['iva'];
	    $idiva = $rowIv['id_iva'];
	     
	    $sumaBase[$idiva]=0;
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
	    $iva_ac = $rowImp2['id_iva'];
	    $sumaBase[$iva_ac]=0;
	    	}
	}
		 $cantidadImpuestos = count($listadoImpuestos['iva']);
		 $anchoColumnas = 120/$cantidadImpuestos;
                    $result2 = mysql_query($sql2) or die(mysql_error());     
                        
                    $pdf->Cell(105,10,'# Producto',$borde2,0,'L',$relleno2);
                    $pdf->Cell(20,10,'Cantidad',$borde2,0,'L',$relleno2);
                    $pdf->Cell(35,10,'Valor Unitario',$borde2,0,'L',$relleno2);
        for($e =0; $e<$cantidadImpuestos; $e++){
    	   	$pdf->Cell($anchoColumnas,10,'Total '.$listadoImpuestos['iva'][$e].'%',$borde2,0,'R',$relleno2);
    	   	$listado_anchos[]=$anchoColumnas;
    	   	$listado_alineacion[]='R';
        }            
                    
                   
                    $pdf->Ln();
                    
                    $totalBase=0;
                    $totalRetenido='0';
                    $sumaBase0='0';
                    $sumaBase12=0;
                    $sumaBasePor=0;
            $fp_ing=0;
            	$pdf->SetX(5);
	$pdf->SetFont('Arial','B',10);
	//2 120/2 =60 
	//3 120/3 = 40
	//4 120/4 = 30
$pdf->SetWidths($listado_anchos);
$pdf->SetAligns($listado_alineacion);
$pdf->SetLineHeight(5);

            	while($row2 = mysql_fetch_array($result2)) {
            	  $data = array();     
                    
                    // $pdf->Cell(155,10,substr(utf8_decode($row2['producto']),0,75),$borde3,0,'L',$relleno3);
                    // $pdf->Cell(20,10,$row2['total'],$borde3,0,'C',$relleno3);
                    // $pdf->Cell(35,10,$row2['v_unitario'],$borde3,0,'R',$relleno3);
                    
                    // if($row2['IVA']=='No'){
                    //     $vTotal0 = number_format(($row2['sumatotal']),4,'.','');
                    //     $vTotal12 = '0.00';
                    // }else{
                    //     $vTotal12 = number_format(($row2['sumatotal']),4,'.','');
                    //     $vTotal0 = '0.00';
                    // }
                    
                    
                    
                    // $pdf->Cell(35,10,$vTotal0,$borde3,0,'R',$relleno3);
                    // $pdf->Cell(35,10,$vTotal12,$borde3,0,'R',$relleno3);
                    $data[] = utf8_decode($row2['producto']);
                    $data[] = $row2['total'];
                    $data[] = $row2['v_unitario'];
                    for($e =0; $e<$cantidadImpuestos; $e++){
                        $iva_ac =$listadoImpuestos['id_iva'][$e];
                        
                	    if($iva_ac== $row2['IVA']){
                	        $data[] = number_format(($row2['sumatotal']),4,'.','');
                	        $sumaBase[$iva_ac]  = $sumaBase[$iva_ac] + $row2['sumatotal'];
                	    }else{
                	        $data[] = number_format(0,4,'.','');
                	    }
                	    
                	     
                    }
                   
$pdf->SetX(5);
                     $pdf->Row($data);
                     
                    $pdf->Ln();
                    
                   
                  
                    
                     $fp_ing++;  
            	}  
  
    
            	    $pdf->Cell(160,10,'','',0,'R','');
            	     for($e =0; $e<$cantidadImpuestos; $e++){
                       $iva_act= $listadoImpuestos['id_iva'][$e];
                	    $pdf->Cell($anchoColumnas,10,'$ '.number_format($sumaBase[$iva_act] ,4,'.',''),$borde,0,'R',$relleno);
                	    
                    }
                    
            	  
            	    $pdf->Ln();
                    $pdf->Ln(4);

    
    

    # Nombre del archivo PDF #
    $pdf->Output("I","Cantidad_ventas_por_producto_desde_".$_GET['txtFechaDesde']."_hasta_".$_GET['txtFechaHasta'].".pdf",true);