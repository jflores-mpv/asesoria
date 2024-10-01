<?php
   session_start();
	# Incluyendo librerias necesarias #
	
	require_once('../conexion.php');
    // require "./code128.php";
    require "./pdf_mc_table_compras_areas.php";
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

  
    $ciudad= ($_GET['cbciudad']);
    $txtClientes = $_GET['txtClientes']; 
    $tipoDoc= ($_GET['tipoDoc']);
    $txtUsuarios= $_GET['txtUsuarios'];
    $estado= $_GET['estado'];
    $emision = $_GET['emision'];
    $autorizacion= $_GET['autorizacion'];

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
    
    $sql = " SELECT ventas.id_venta, detalle_ventas.id_servicio, productos.producto 
    FROM `ventas` 
    INNER JOIN detalle_ventas ON detalle_ventas.id_venta = ventas.id_venta 
    INNER JOIN productos ON productos.id_producto = detalle_ventas.id_servicio
    
    WHERE ventas.id_empresa=$sesion_id_empresa AND
		DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d') >='".$_GET['txtFechaDesde']."' and
		DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d')<='".$_GET['txtFechaHasta']."'  ";
		if($_GET['idProducto']!=0){
		    $sql .= "	and productos.id_producto ='".$_GET['idProducto']."'  ";
		}
	$sql .= "	GROUP BY productos.id_producto";
// echo $sql;
// exit;
    $result = mysql_query($sql) or die(mysql_error());
    $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
    $numero = 0;
    $currentCodImp = null;
 
    while($row = mysql_fetch_array($result)) {
	     
	    
	            
	        $pdf->Cell(100,10,substr($row['producto'],0,40),$borde,0,'L',$relleno);
            $pdf->Ln();
            
	        $sql2 = "SELECT
    ventas.id_venta,
    ventas.fecha_venta,
    ventas.`numero_factura_venta` AS ventas_numero_factura_venta, 
    detalle_ventas.id_servicio,
    detalle_ventas.cantidad,
    detalle_ventas.detalle,
    detalle_ventas.v_unitario,
    detalle_ventas.descuento,
    detalle_ventas.v_total,
    productos.producto,
    clientes.id_cliente,
    CONCAT(clientes.nombre,' ' ,clientes.apellido)    as clientes_nombre,
     establecimientos.codigo as establecimientos_codigo, 
    emision.codigo as emision_codigo
FROM
    `ventas`
INNER JOIN detalle_ventas ON detalle_ventas.id_venta = ventas.id_venta
INNER JOIN productos ON productos.id_producto = detalle_ventas.id_servicio
INNER JOIN clientes ON clientes.id_cliente = ventas.id_cliente
INNER JOIN establecimientos ON establecimientos.id= ventas.codigo_pun
INNER JOIN emision ON emision.id=ventas.codigo_lug

WHERE
    ventas.id_empresa = $sesion_id_empresa 
        AND DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d') >= DATE_FORMAT('".$fecha_desde."' , '%Y-%m-%d') 
    AND DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d') <= DATE_FORMAT('".$fecha_hasta."', '%Y-%m-%d')  
    and  detalle_ventas.id_servicio ='".$row['id_servicio']."' ";


    if ($ciudad!='0' && $ciudad!='' ){
		$sql2.= " and clientes.`id_ciudad`='".$ciudad."' "; 
	}
	if ($txtClientes!='0' ){
		$sql2.= " and clientes.`id_cliente` = '".$txtClientes."' "; 
	}
	if ($tipoDoc!='0'){
		$sql2.= " and ventas.`tipo_documento`='".$tipoDoc."' "; 
	}

    if ($txtUsuarios!='0'){
		$sql2.= " and ventas.`id_usuario`='".$txtUsuarios."' "; 
	}
    if ($estado !='0'){
		$sql2.= " and ventas.`estado`='".$estado."' "; 
	}
	if ($emision !='0'&& $emision!='GLOBAL'){
		$sql2.= " and emision.`id` ='".$emision."' "; 
	}

    if ($autorizacion =='1'){
		$sql2.= " and ventas.`Autorizacion`!='' "; 
	}else if ($autorizacion =='2'){
	    	$sql2.= " and ventas.`Autorizacion` IS NULL "; 
	}
            
                    $result2 = mysql_query($sql2) or die(mysql_error());     
                
                           $pdf->Cell(31,10,'# Venta',$borde2,0,'L',$relleno2);
                    $pdf->Cell(32,10,' Fecha',$borde2,0,'L',$relleno2);
                    $pdf->Cell(35,10,'Cliente',$borde2,0,'L',$relleno2);
                     $pdf->Cell(35,10,'Detalle',$borde2,0,'L',$relleno2);
                      $pdf->Cell(10,10,'Lote',$borde2,0,'C',$relleno2);
                    $pdf->Cell(20,10,'Calidad',$borde2,0,'C',$relleno2);
                    $pdf->Cell(20,10,'F. Elab.',$borde2,0,'C',$relleno2);
                    $pdf->Cell(20,10,'F. Cadu.',$borde2,0,'C',$relleno2);
                    $pdf->Cell(20,10,'Cantidad',$borde2,0,'L',$relleno2);
                    $pdf->Cell(30,10,'# Valor Unitario',$borde2,0,'R',$relleno2);
                    $pdf->Cell(35,10,'V. Total',$borde2,0,'R',$relleno2);
                    $pdf->Ln();
                    
                      

                    
                    $totalBase=0;
                    $totalRetenido='0';
            
            $sumaBaseImp=0;
            $sumaBasePor=0;
              
$data2 = array();
            	    while($row2 = mysql_fetch_array($result2)) {
            	        
            	    $numeroFactura= $row2['ventas_numero_factura_venta'];
        $estCod= $row2['establecimientos_codigo'];
            $emiCod= $row2['emision_codigo'];
	     $numeroFactura= ceros($numeroFactura);
            	    $numeroVenta=  $estCod."-".$emiCod."-".$numeroFactura;
            	    
            	$data2[$num][]=$numeroVenta;
        $data2[$num][]=$row2['fecha_venta'];
        $data2[$num][]=utf8_decode($row2['clientes_nombre']);
        $data2[$num][]=utf8_decode($row2['detalle']);
        $data2[$num][]=$row2['cantidad'];
        $data2[$num][]=$row2['v_unitario'];
        $data2[$num][]=$row2['v_total'];
        
        $data2[$num][]=$row2['numero_lote'];
        $data2[$num][]=$row2['calidad'];
        $data2[$num][]=$row2['fecha_elaboracion'];
        $data2[$num][]=$row2['fecha_caducidad'];

                   
                    $sumaBaseImp= $sumaBaseImp + $row2['v_unitario'];
                    $sumaBasePor= $sumaBasePor + $row2['v_total'];
                    $num++;
            	}
    
                $pdf->SetWidths(array(31,32,35,35,10,20,20,20,20,30,35));
            
            	 
            $pdf->SetLineHeight(5);

            	 foreach($data2 as $item){
                $cantidadItem++;
      
            $pdf->Row(array(
                $item[0],
                $item[1],
                $item[2],
                $item[3],
                 $item[7],
                $item[8],
                $item[9],
                $item[10],
                
                $item[4],
                $item[5],
                $item[6],
              ));
        
        

    }
    
         $pdf->Cell(223,10,'','',0,'R','');
                    $pdf->Cell(30,10,$sumaBaseImp,$borde,0,'R',$relleno);
                    $pdf->Cell(35,10,$sumaBasePor,$borde,0,'R',$relleno);
            	    $pdf->Ln();
                    $pdf->Ln(4);
                
            
            	   

    }
    

    # Nombre del archivo PDF #
    $pdf->Output("I","Ventas_por_producto_desde_".$_GET['txtFechaDesde']."_hasta_".$_GET['txtFechaHasta'].".pdf",true);