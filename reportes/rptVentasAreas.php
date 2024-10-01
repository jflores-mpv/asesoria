<?php
error_reporting(0);
   session_start();
	# Incluyendo librerias necesarias #
	
	require_once('../conexion.php');
    // require "./code128.php";
    include('pdf_mc_table.php');
    
    
     $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
  	 
       $matrizador= $_GET['matrizador'];
       if(trim($matrizador)==''){
           $matrizador=0;
       }
     $numero_libro= $_GET['numero_libro'];
       if(trim($numero_libro)==''){
           $numero_libro=0;
       }
    $fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
    $fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
    $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
    $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
    $emision = $_GET['emision'];
    $cbciudad = $_GET['cbciudad'];
    $tipoDoc= ($_GET['tipoDoc']);
    $txtUsuarios= $_GET['txtUsuarios'];
    $estado= $_GET['estado'];
    $emision = $_GET['emision'];
    $autorizacion= $_GET['autorizacion'];
    $txtClientes = $_GET['txtClientes']; 
    
    function ceros($valor){
               $s='';
        for($i=1;$i<=9-strlen($valor);$i++)
                $s.="0";
        return $s.$valor;
    }
    
    $resultDetallesVentaC=mysql_query( "select COUNT(*) as detalles from  centro_costo where empresa='".$sesion_id_empresa."' ");
    $ODetallesVentaC=mysql_fetch_array($resultDetallesVentaC);
    
     $numDetalles=$ODetallesVentaC['detalles'];
    $altura = $numDetalles*4;
 $listadoSubtotales = array();
  $sqlImpuestos = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='".$sesion_id_empresa."'";
	$resulImpuestos = mysql_query($sqlImpuestos);
	$listadoImpuestos = array();
	$existe12= false;
	while($rowIv = mysql_fetch_array($resulImpuestos) ){
	    
	    $listadoImpuestos['id_iva'][]= $rowIv['id_iva'];
	    $listadoImpuestos['iva'][]= $rowIv['iva'];
	     $listadoImpuestos['suma_iva'][]= 0;
	    $idiva = $rowIv['id_iva'];
	     $listadoSubtotales[$idiva] = 0;
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
	    $listadoImpuestos['suma_iva'][]= 0;
	    $iva_ac = $rowImp2['id_iva'];
	    $sumaBase[$iva_ac]=0;
	     $listadoSubtotales[$iva_ac] = 0;
	    	}
	}
		 $cantidadImpuestos = count($listadoImpuestos['iva']);


    /*----------  Detalles de la tabla  ----------*/
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
	
	 $sqlDV = "select *,";
	 for($e =0; $e<$cantidadImpuestos; $e++){
	     $ida = $listadoImpuestos['id_iva'][$e];
    	   	$sqlDV .= "
    SUM(if(detalle_ventas.tarifa_iva='$ida',
    detalle_ventas.v_total, 0)) AS total$ida, ";
        }      
        
    
    
    
      $sqlDV .= " SUM(v_total) as total ,
      SUM(detalle_ventas.total_iva) as total_iva ,
    centro_costo.descripcion,
    fecha_venta 
    from  ventas 
    INNER JOIN `clientes` clientes  ON ventas.`id_cliente` = clientes.`id_cliente` 
    INNER JOIN detalle_ventas ON detalle_ventas.id_venta =ventas.id_venta
    INNER JOIN productos ON productos.id_producto = detalle_ventas.id_servicio 
    INNER JOIN centro_costo ON centro_costo.id_centro_costo=detalle_ventas.idBodega
    INNER JOIN `emision` emision      ON ventas.`codigo_lug` = emision.`id`";
    
     
     $sqlDV.= " where ventas.id_empresa='".$sesion_id_empresa."'   ";
    
    
   
    if($fecha_desde!='' && $fecha_hasta!=''){
        $sqlDV .=" AND DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d') >='".$_GET['txtFechaDesde']."' AND DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d')<='".$_GET['txtFechaHasta']."' ";
    }

    if ($txtClientes!='0' ){
		$sqlDV.= " and clientes.`id_cliente` = '".$txtClientes."' "; 
	}
    $txtProductos  = $_GET['idProducto'];

    if ($txtProductos!='0' && $txtProductos!='A' && $txtProductos!='B' ){
		$sqlDV.= " and productos.id_producto = '".$txtProductos."' "; 
	}
    
    
    if ($cbciudad!='0' && $cbciudad!='' ){
		$sqlDV.= " and clientes.`id_ciudad`='".$cbciudad."' "; 
	}
    if ($tipoDoc!='0'){
		$sqlDV.= " and ventas.`tipo_documento`='".$tipoDoc."' "; 
	}
    if ($txtUsuarios!='0'){
		$sqlDV.= " and ventas.`id_usuario`='".$txtUsuarios."' "; 
	}
    if ($estado !='0'){
		$sqlDV.= " and ventas.`estado`='".$estado."' "; 
	}
    if ($emision !='0'){
		$sqlDV.= " and emision.`id` ='".$emision."' "; 
	}
    if ($autorizacion =='1'){
		$sqlDV.= " and ventas.`Autorizacion`!='' "; 
	}else if ($autorizacion =='2'){
	    	$sqlDV.= " and ventas.`Autorizacion` IS NULL "; 
	}
		$vendedor_id = trim($_GET['vendedor']);
if ($vendedor_id!=0 && $vendedor_id!=''){
       $sqlDV.= " and clientes.`id_vendedor`='".$vendedor_id."' "; 
   }
    $sqlDV .="   and ventas.id_venta NOT IN (".$listaSD.")   GROUP BY idBodega ";
    // echo $sqlDV;
    
    if($sesion_id_empresa==41){
        // echo $sqlDV;
    }
    $resultDetalleVenta=mysql_query( $sqlDV);

    $totalareas='0';
   $subtotal0=0;
   $subtotal12=0;


        $contadorF=0;
      $z= 0;
      $data2=array();
    $iva_final=0;
    $subtotalFinal=0;
    while($ODetalleVenta=mysql_fetch_array($resultDetalleVenta)){
        
      $data2[$z][]=utf8_decode($ODetalleVenta['descripcion']);
      // $data2[$z][]=" Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi, adipisci minus! Culpa possimus modi corporis reiciendis doloremque nam quod. Ipsa ullam pariatur d";
      $data2[$z][]=number_format($ODetalleVenta['total'],4,'.','');

      $totalareas+=number_format($ODetalleVenta['total'],4,'.','');
        
       
         
          for($e =0; $e<$cantidadImpuestos; $e++){
	     $ida = $listadoImpuestos['id_iva'][$e];
	     
	     $listadoSubtotales[$ida] += $ODetalleVenta['total'.$ida];
    $subtotalFinal += $ODetalleVenta['total'.$ida];
        }      
        
        $iva_final += $ODetalleVenta['total_iva'];
         $contadorF++;
         $z++;
         
    }



   // Define el alto de cada fila en milímetros (ajústalo según tus necesidades)
   $rowHeight =5;
   
   // Inicializa la variable para el alto total del contenido
   $totalHeight = 0;
   $pdf = new PDF_MC_Table();
   $pdf->SetFont('Arial','B',9);
   // Recorre los datos para calcular el alto total del contenido
   foreach ($data2 as $row) {
       $maxLines = 0; // Número máximo de líneas en una fila
           $text = $row[0];
      //  echo '|';
        $maxLines = max($maxLines, $pdf->NbLines(49, $text));
       $totalHeight += $maxLines * $rowHeight;
   }
  //  echo '|';
  //  echo $totalHeight;
  //  echo '|';
      // $pdf = new PDF_MC_Table('P','mm',array(80,130+$altura));
      // $pdf = new PDF_MC_Table();
        // $pdf = new PDF_MC_Table('P','mm',array(80,130+$totalHeight));
      $pdf->SetMargins(4,10,4);
      // $pdf->AddPage();
      $pdf->AddPage('P', array(80, 105+$totalHeight+($cantidadImpuestos*5)));
      
      
      $resultEmpresa=mysql_query( "select * from empresa where id_empresa='".$sesion_id_empresa."' ");
    $Oempresa=mysql_fetch_array($resultEmpresa);
      
      # Encabezado y datos de la empresa #
      $pdf->SetFont('Arial','B',10);
      $pdf->SetTextColor(0,0,0);
      $pdf->MultiCell(0,5,utf8_decode(strtoupper($Oempresa['nombre'])),0,'C',false);
      $pdf->MultiCell(0,5,utf8_decode(strtoupper($Oempresa['razonSocial'])),0,'C',false);
      
      $pdf->SetFont('Arial','',9);
  
      $pdf->Cell(0,5,utf8_decode("------------------------------------------------------"),0,0,'C');
      $pdf->Ln(5);
      $pdf->MultiCell(0,5,utf8_decode(strtoupper('REPORTE DE VENTAS RESUMEN')),0,'C',false);
        $pdf->MultiCell(0,5,'Fecha Desde: '.$_GET['txtFechaDesde'],0,'C',false);
      $pdf->MultiCell(0,5,'Fecha Hasta: '.$_GET['txtFechaHasta'],0,'C',false);
      $pdf->Cell(0,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');
      $pdf->Ln(3);
      
      # Tabla de productos #
      $pdf->Cell(45,5,utf8_decode("AREA"),0,0,'L');
      $pdf->Cell(20,5,utf8_decode("VALOR"),0,0,'L');
  
      $pdf->Ln(3);
      $pdf->Cell(72,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');
      $pdf->Ln();
      $pdf->SetWidths(array(45, 20));
      $pdf->SetAligns(array('L', 'C'));
      $pdf->SetLineHeight(5);
      $pdf->SetFont('Arial','',9);
    foreach($data2 as $item)
{

    $pdf->Row(array(
        $item[0],
        $item[1],
        $item[2],
        $item[3],
        $item[4],
        $item[5],
      ));

 
}
$yposdinamic = $pdf->GetY();
$pdf->setY($yposdinamic);
    /*----------  Fin Detalles de la tabla  ----------*/


    $pdf->Cell(72,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');

    $pdf->Ln(5);
    
    
    // $Oventa['fecha_venta']
    
    # Impuestos & totales #
    // $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
   $sumaIva=0;
  
    for($e =0; $e<$cantidadImpuestos; $e++){
        $ida2 = $listadoImpuestos['id_iva'][$e];
	     $ida = $listadoImpuestos['iva'][$e];
	      $listadoImpuestos['suma_iva'][$e]=$listadoSubtotales[$ida2] *($listadoImpuestos['iva'][$e]/100);
    $pdf->Cell(40,5,utf8_decode("SUBTOTAL ".$ida."%"),0,0,'R');
    $pdf->Cell(5,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(20,5,"$" .number_format($listadoSubtotales[$ida2],4,'.',''),0,0,'L');
    $pdf->Ln(5);
    $sumaIva = $sumaIva + $ODetalleVenta['total'.$ida2];
        }      
        
   
           
    
       

   
    
      $pdf->Cell(40,5,utf8_decode("SUBTOTAL "),0,0,'R');
    $pdf->Cell(5,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(20,5,"$" .number_format($subtotalFinal,4,'.',''),0,0,'L');
    $pdf->Ln(5);
    
     for($e =0; $e<$cantidadImpuestos; $e++){
        $ida2 = $listadoImpuestos['id_iva'][$e];
	     $ida = $listadoImpuestos['iva'][$e];
	     
	     if($ida!=0){
	         $pdf->Cell(40,5,utf8_decode("IVA ".$ida."%"),0,0,'R');
            $pdf->Cell(5,5,utf8_decode(""),0,0,'C');
            $pdf->Cell(20,5,"$" .number_format($listadoImpuestos['suma_iva'][$e],4,'.',''),0,0,'L');
            $pdf->Ln(5);
	     }
    
        } 
    //   $pdf->Cell(40,5,utf8_decode("IVA "),0,0,'R');
    // $pdf->Cell(5,5,utf8_decode(""),0,0,'C');
    // $pdf->Cell(20,5,"$" .$iva_final,0,0,'L');
    // $pdf->Ln(5);
    
  
    
    $pdf->Cell(40,5,utf8_decode("TOTAL"),0,0,'R');
    $pdf->Cell(5,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(20,5,"$" .number_format($subtotalFinal+$iva_final,4,'.',''),0,0,'L');

    # Nombre del archivo PDF #
    $pdf->Output("I","Ventas_por_areas_desde_".$_GET['txtFechaDesde']."_hasta_".$_GET['txtFechaHasta'].".pdf",true);
   