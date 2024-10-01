<?php
   session_start();
	# Incluyendo librerias necesarias #
	
	require_once('../conexion.php');
    require "./code128.php";
    
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];

    
    function ceros($valor){
               $s='';
            for($i=1;$i<=9-strlen($valor);$i++)
                $s.="0";
            return $s.$valor;
        }
    
    $fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
    $fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
    $txtProveedor =  isset($_GET['txtProveedor']) ?$_GET['txtProveedor']:'';
    
    $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
    $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
$tipoDoc= ($_GET['tipoDoc']);
$txtUsuarios= $_GET['txtUsuarios'];
$emision = $_GET['emision'];
$txtClientes = $_GET['txtClientes']; 
$cbciudad = $_GET['cbciudad'];

       $matrizador= $_GET['matrizador'];
       if(trim($matrizador)==''){
           $matrizador=0;
       }
       $numero_libro= $_GET['numero_libro'];
       if(trim($numero_libro)==''){
           $numero_libro=0;
       }
       
       
    $pdf = new PDF_Code128('L','mm','A4');
    $pdf->SetMargins(4,10,4);
    $pdf->AddPage();
    
    
    $resultEmpresa=mysql_query( "select * from empresa where id_empresa='".$sesion_id_empresa."' ");
	$Oempresa=mysql_fetch_array($resultEmpresa);
    
    # Encabezado y datos de la empresa #
    $pdf->SetFont('Arial','B',10);
    
    $pdf->SetTextColor(0,0,0);
    
    $pdf->MultiCell(0,5,utf8_decode(strtoupper($Oempresa['nombre'])),0,'C',false);
    
    $pdf->MultiCell(0,5,utf8_decode(strtoupper($Oempresa['razonSocial'])),0,'C',false);
    
    $pdf->MultiCell(0,5,'Reporte de ventas por producto',0,'C',false);
    
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
    
    
    
	
	$estado= $_GET['estado'];
      $autorizacion= $_GET['autorizacion'];
    $sql = " SELECT 
    ventas.id_venta, 
    ventas.numero_factura_venta,
    ventas.`fecha_venta`,
    ventas.`sub0`,
    ventas.`sub12`,
    detalle_ventas.id_servicio, 
    detalle_ventas.v_unitario, 
    detalle_ventas.v_total, 
    detalle_ventas.descuento,
    detalle_ventas.cantidad,
    productos.producto ,
    productos.codigo,
    productos.iva 
    FROM `ventas` 
    INNER JOIN detalle_ventas ON detalle_ventas.id_venta = ventas.id_venta 
    INNER JOIN productos ON productos.id_producto = detalle_ventas.id_servicio
    INNER JOIN `emision` emision      ON ventas.`codigo_lug` = emision.`id` 
    INNER JOIN `clientes` clientes  ON ventas.`id_cliente` = clientes.`id_cliente` ";
    	  

    $sql .= " WHERE ventas.id_empresa=$sesion_id_empresa AND
		DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d') >='".$_GET['txtFechaDesde']."' and
		DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d')<='".$_GET['txtFechaHasta']."'  ";
		
  

  if(isset($_GET['idProducto'])){
    if($_GET['idProducto']!=0){
      $sql .= "	and productos.id_producto ='".$_GET['idProducto']."'  ";
    }
  }
  if ($txtClientes!='0' ){
		$sql.= " and clientes.`id_cliente` = '".$txtClientes."' "; 
	}
  if ($cbciudad!='0' && $cbciudad!='' ){
		$sql.= " and clientes.`id_ciudad`='".$cbciudad."' "; 
	}
	if ($tipoDoc!='0'){
		$sql.= " and ventas.`tipo_documento`='".$tipoDoc."' "; 
	}
	if ($estado !='0' && $estado !='D'){
		$sql.= " and ventas.`estado`='".$estado."' "; 
	}
	
	if ($autorizacion =='1'){
		$sql.= " and ventas.`Autorizacion`!='' "; 
	}else if ($autorizacion =='2'){
	    	$sql.= " and ventas.`Autorizacion` IS NULL "; 
	}

	if ($emision !='0' && $emision!='GLOBAL'){
		$sql.= " and emision.`id` ='".$emision."' "; 
	}
	if ($txtUsuarios!='0'){
		$sql.= " and ventas.`id_usuario`='".$txtUsuarios."' "; 
	}
	
	$vendedor_id = trim($_GET['vendedor']);
    if ($vendedor_id!=0 && $vendedor_id!=''){
       $sql.= " and clientes.`id_vendedor`='".$vendedor_id."' "; 
   }
	$sql .= "  ORDER BY ventas.id_venta";

    // echo $sql;
    // exit;
    $result = mysql_query($sql) or die(mysql_error());
    $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
    $numero = 0;

    $venta='';
    $pieVenta='';
 $sumaSub12=0;
            $sumaSub0=0;
	         while($row2 = mysql_fetch_array($result)) {
	             
	             if($row2['id_venta']!=$pieVenta && $numero>0  ){
	                 
	                 
	                 if($sub0 !=   number_format($sumaSub0, 2, '.', ' ') ){
	                      $pdf->SetFillColor(200,85,85);
	                 }
	                 
            	 $pdf->Cell(150,10,'','',0,'R','');
            	     $pdf->Cell(40,10,'Subtotal venta 0 ',$borde,0,'R',$relleno);
                    $pdf->Cell(25,10,$sub0,$borde,0,'R',$relleno);
                     $pdf->Cell(40,10,'Subtotal detalle 0 ',$borde,0,'R',$relleno);
                    $pdf->Cell(25,10,$sumaSub0,$borde,0,'R',$relleno);
            	    $pdf->Ln();
            	    $revisar='';
            	      if($sub12 != number_format($sumaSub12, 2, '.', ' ')){
	                      $pdf->SetFillColor(200,85,85);
	                      $revisar='descuadre**';
	                 }else{
	                      $pdf->SetFillColor(200,200,200);
	                      $revisar='';
	                 }
	                 
            	   // $pdf->Cell(150,10,'','',0,'R','');
            	    $pdf->Cell(130,10,'','',0,'R','');
            	    $pdf->Cell(20,10,$revisar,'',0,'R','');
            	     $pdf->Cell(40,10,'Subtotal venta 12 ',$borde,0,'R',$relleno);
                    $pdf->Cell(25,10,$sub12,$borde,0,'R',$relleno);
                      $pdf->Cell(40,10,'Subtotal detalle 12 ',$borde,0,'R',$relleno);
                    $pdf->Cell(25,10,$sumaSub12,$borde,0,'R',$relleno);
            	    $pdf->Ln();
                    $pdf->Ln(4);
                    
                    // $pieVenta = $row2['id_venta'];
                     $pdf->SetFillColor(200,200,200);
                }
	             
	             
	            
	             
	             if($row2['id_venta']!=$venta ){
	                  if($pdf->GetY()>135){
	                 $pdf->AddPage();
	             }
	                   $pdf->Cell(100,10,substr($row2['numero_factura_venta'],0,40),$borde,0,'L',$relleno);
            $pdf->Ln();
            
                        
                    $pdf->Cell(40,10,'# Codigo',$borde2,0,'L',$relleno2);
                   
                    $pdf->Cell(110,10,'Producto',$borde2,0,'L',$relleno2);
                    $pdf->Cell(20,10,'Cantidad',$borde2,0,'L',$relleno2);
                     $pdf->Cell(40,10,' Descuento',$borde2,0,'L',$relleno2);
                    $pdf->Cell(35,10,'# Valor Unitario',$borde2,0,'R',$relleno2);
                    $pdf->Cell(35,10,'V. Total',$borde2,0,'R',$relleno2);
                    $pdf->Ln();
                    
                    $totalBase=0;
                    $totalRetenido='0';
            
            $sumaSub12=0;
            $sumaSub0=0;
            $venta= $row2['id_venta'];
	             }

                    $pdf->Cell(40,10,$row2['codigo'],$borde3,0,'L',$relleno3);
                   
                    $pdf->Cell(110,10,utf8_decode($row2['producto']),$borde3,0,'L',$relleno3);
                    $pdf->Cell(20,10,$row2['cantidad'],$borde3,0,'C',$relleno3);
                     $pdf->Cell(40,10,$row2['descuento'],$borde3,0,'L',$relleno3);
                    $pdf->Cell(35,10,number_format($row2['v_unitario'],4,'.',''),$borde3,0,'R',$relleno3);
                    $pdf->Cell(35,10,number_format($row2['v_total'],4,'.',''),$borde3,0,'R',$relleno3);
                    $pdf->Ln();
               
                    if($row2['iva'] == 'Si' ){
                          $sumaSub12= $sumaSub12 + number_format($row2['v_total'],4,'.','');
                    }else{
                        
                          $sumaSub0= $sumaSub0 + number_format($row2['v_total'],4,'.','');
                    }
                  
                    
                    
                    $numero++;   
                     $sub0= $row2['sub0'];
                 $sub12= $row2['sub12'];
                   if( $numero==$numero_filas  ){
            	     if($sub0 !=   number_format($sumaSub0, 2, '.', ' ') ){
	                      $pdf->SetFillColor(200,85,85);
	                 }
	                 
            	 $pdf->Cell(150,10,'','',0,'R','');
            	     $pdf->Cell(40,10,'Subtotal venta 0 ',$borde,0,'R',$relleno);
                    $pdf->Cell(25,10,$sub0,$borde,0,'R',$relleno);
                     $pdf->Cell(40,10,'Subtotal detalle 0 ',$borde,0,'R',$relleno);
                    $pdf->Cell(25,10,$sumaSub0,$borde,0,'R',$relleno);
            	    $pdf->Ln();
            	    $revisar='';
            	      if($sub12 != number_format($sumaSub12, 2, '.', ' ')){
	                      $pdf->SetFillColor(200,85,85);
	                      $revisar='**';
	                 }else{
	                      $pdf->SetFillColor(200,200,200);
	                      $revisar='';
	                 }
	                 
            	    $pdf->Cell(130,10,'','',0,'R','');
            	    $pdf->Cell(20,10,$revisar,'',0,'R','');
            	     $pdf->Cell(40,10,'Subtotal venta 12 ',$borde,0,'R',$relleno);
                    $pdf->Cell(25,10,$sub12,$borde,0,'R',$relleno);
                      $pdf->Cell(40,10,'Subtotal detalle 12 ',$borde,0,'R',$relleno);
                    $pdf->Cell(25,10,$sumaSub12,$borde,0,'R',$relleno);
            	    $pdf->Ln();
                    $pdf->Ln(4);
                    // $pieVenta = $row2['id_venta'];
                    
                     $pdf->SetFillColor(200,200,200);
                }

               $pieVenta = $row2['id_venta'];//cambio
	         }

    # Nombre del archivo PDF #
    $pdf->Output("I","Ventas_por_producto_desde_".$_GET['txtFechaDesde']."_hasta_".$_GET['txtFechaHasta'].".pdf",true);