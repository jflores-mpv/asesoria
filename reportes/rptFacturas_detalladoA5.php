<?php

	require('fpdf17/fpdf.php');
	require('../clases/funciones.php');
	require_once('../conexion.php');
	session_start();
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    $id_venta = $_GET["txtComprobanteNumero"];
    $sql = "SELECT ventas.`id_venta` AS ventas_id_venta, ventas.`numero_factura_venta` AS ventas_numero_factura_venta, 
    ventas.`id_cliente` AS ventas_id_cliente, ventas.`fecha_venta` AS ventas_fecha_venta, ventas.`sub_total` AS ventas_sub_total, 
    ventas.`id_iva` AS ventas_id_iva, ventas.`total` AS ventas_total, clientes.`id_cliente` AS clientes_id_cliente, 
    clientes.`nombre` AS clientes_nombre, clientes.`apellido` AS clientes_apellido, clientes.`direccion` AS clientes_direccion, clientes.`cedula` AS clientes_cedula, clientes.`numero_casa` AS clientes_numero_casa, clientes.`email` AS clientes_email, clientes.`telefono` AS clientes_telefono,   establecimientos.codigo as establecimientos_codigo, emision.codigo as emision_codigo , empresa.nombre as empresa_nombre, empresa.direccion as empresa_direccion, empresa.contribuyente as empresa_contribuyente ,empresa.Ocontabilidad as empresa_ocontabilidad, empresa.imagen as empresa_imagen 
        
    FROM `clientes` clientes 
    INNER JOIN `ventas` ventas ON clientes.`id_cliente` = ventas.`id_cliente` 
    INNER JOIN establecimientos ON establecimientos.id= ventas.codigo_lug 
    INNER JOIN emision ON emision.id=ventas.codigo_pun 
    INNER JOIN empresa ON empresa.id_empresa=ventas.id_empresa " ;
  
    
    $sql .= " where ventas.`id_empresa`='".$sesion_id_empresa."' ";
     $sql .= " and ventas.`id_venta`='".$id_venta."' ";
         


       $sql .= " order by clientes.`cedula`, ventas.`id_venta` "; 
       $result = mysql_query($sql) or die(mysql_error());
       $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
       $numero = 0;
      
      // echo $sql."***    ".$numero_filas;
 $data = array();
        while($row = mysql_fetch_array($result)) //for mayor    for($i=0; $i<$numero_filas; $i++)
       {
           $numero ++;
    
        $nombreEmpresa=  $row["empresa_nombre"];  
        $direccionEmpresa=  $row["empresa_direccion"];  
        $imagenEmpresa=  $row["empresa_imagen"]; 
        $contribuyenteEmpresa=  $row["empresa_contribuyente"];  
        $ocontabilidadEmpresa=  $row["empresa_ocontabilidad"];
         

        $nombreCliente=  $row["clientes_nombre"];   
        $apellidoCliente=  $row["clientes_apellido"];
        $direccionCliente=  $row["clientes_direccion"];          
        $cedulaCliente=  $row["clientes_cedula"]; 
        $fechaVenta= $row['ventas_fecha_venta'];
        $telefonoCliente= $row['clientes_telefono'];
        $emailCliente= $row['clientes_email'];
     
        $numeroFactura= $row['ventas_numero_factura_venta'];
        $subtotalF= $row['ventas_sub_total'];
        $totalF= $row['ventas_total'];
        $estCod= $row['establecimientos_codigo'];
        $emiCod= $row['emision_codigo'];
    
       }
       function ceros($valor){
        $s='';
     for($i=1;$i<=9-strlen($valor);$i++)
         $s.="0";
     return $s.$valor;
 }
	class PDF extends FPDF
	{
	
	}

    $pdf = new PDF('P','mm','A');
    $pdf->SetAutoPageBreak(true ,20);
        
$pdf->AddPage();
    

/// Apartir de aqui empezamos con la tabla de productos
// $pdf->setY($iTabla+5);
$pdf->setX(135);
    $pdf->Ln();
/////////////////////////////
//// Array de Cabecera
$header = array("Cod.", "Producto","Cantidad","Valor Unitario","Total");


$sql2="
SELECT detalle_ventas.`id_detalle_venta`, detalle_ventas.`cantidad`, detalle_ventas.`estado`, detalle_ventas.`v_unitario`, detalle_ventas.`descuento`, detalle_ventas.`v_total`, detalle_ventas.`id_venta`, detalle_ventas.`id_servicio`, detalle_ventas.`tipo_venta`, productos.producto 

FROM `detalle_ventas` 

INNER JOIN productos ON productos.id_producto= detalle_ventas.id_servicio 

WHERE detalle_ventas.`id_venta`='$id_venta'; ";
$result2=mysql_query($sql2);
$products = array();
$contadorF=0;
//  echo $sql2;
while($row2=mysql_fetch_array($result2))//permite ir de fila en fila de la tabla
{
   // echo $row2['id_servicio'];
    $products[$contadorF] = array($row2['id_servicio'], $row2['producto'] ,$row2['cantidad'],$row2['v_unitario'],$row2['v_total']);
       
    $contadorF++;
}
$pdf->SetFont('Arial','',7);
    // Column widths
    $w = array(10, 55, 20, 25, 25);
    // Header
    for($i=0;$i<count($header);$i++)
        $pdf->Cell($w[$i],7,$header[$i],1,0,'C');
    $pdf->Ln();
    // Data
    $total = 0;
    foreach($products as $row)
    {
        // $l=strlen($row[1]);
        //  $ancho= ceil($l/35)*4 ;
        //  $variable=4;
        $cellWidth=55;
        $cellHeight=5;
        if($pdf->GetStringWidth($row[1])<$cellWidth){
            $line=1;
        }else{
            $textLength=strlen($row[1]);
            $errMargin=19;
            $startChar=0;
            $maxChar=0;
            $textArray=array();
            $tmpString='';
            while($startChar< $textLength){
                while($pdf->GetStringWidth($tmpString)<($cellWidth-$errMargin)&&($startChar+$maxChar)<$textLength){
                    $maxChar++;
                    $tmpString=substr($row[1],$startChar,$maxChar);
                }
                $startChar= $startChar+$maxChar;
                array_push($textArray,$tmpString);
                $maxChar=0;
                $tmpString='';
            }
            $line=count($textArray);
        }
        
    $ancho = $line*4;
    $variable= 4;
     
        // $ancho= 7;

        $pdf->Cell($w[0],$ancho,$row[0],1);
        $y= $pdf->GetY();
        $pdf->MultiCell($w[1],$variable,$row[1],1,'C');
        $y1= $pdf->GetY();
        $pdf->SetY($y);
        $pdf->SetX(75);
        // $actual=(($y1-$y)/5)*5;
        $pdf->Cell($w[2],$ancho,number_format($row[2]),'1',0,'C');
        $pdf->Cell($w[3],$ancho,"$ ".number_format($row[3],2,".",","),'1',0,'R');
        $pdf->Cell($w[4],$ancho,"$ ".number_format($row[3]*$row[2],2,".",","),'1',0,'R');
        $pdf->SetY($y1);
        // $pdf->Ln();
        $total+=$row[3]*$row[2];
        $y2= $pdf->GetY();
    }
    
/////////////////////////////
//// Apartir de aqui esta la tabla con los subtotales y totales
// $yposdinamic = 10 + (count($products)*3);
$yposdinamic =$y2+2;
// $pdf->Cell(5,20,"Informacion Adicional:");
$pdf->setY($yposdinamic);



$pdf->setX(70);
    $pdf->Ln();
/////////////////////////////
$header = array("", "");
$data2 = array(
	array("Subtotal",$subtotalF),
	array("Descuento", 0),
	array("Impuesto", 0),
	array("Total", $totalF),
);
    // Column widths
    $w2 = array(25, 25);
    // Header

     $pdf->Ln();
    // Data
   
    foreach($data2 as $row)
    {
        
$pdf->setX(95);
        $pdf->Cell($w2[0],6,$row[0],1);
        $pdf->Cell($w2[1],6,"$ ".number_format($row[1], 2, ".",","),'1',0,'R');

        $pdf->Ln();
    }
    $yposdinamic = $pdf->GetY();
    $yposdinamic= $yposdinamic-25;
    $pdf->setY($yposdinamic+5);   
    // $pdf->Ln();
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(0,0,utf8_decode("Información adicional"));
    $pdf->setY($yposdinamic+10);   

        $pdf->Cell(0,0,utf8_decode("Dirección:"));
        $pdf->setY($yposdinamic+8); $pdf->setX(23);
        $pdf->SetFont('Arial','B',7);
        $pdf->MultiCell(70,4, $direccionCliente,0,'J',0,10);
        $pie= $pdf->GetY(); 
        $pdf->setY($pie+2);
        $pdf->SetFont('Arial','',7);
        $pdf->Cell(0,0,utf8_decode("Telefóno:"));
        $pdf->setX(23);
        $pdf->SetFont('Arial','B',7);
        $pdf->Cell(0,0, $telefonoCliente);
        $pdf->setY($pie+7);
        $pdf->SetFont('Arial','',7);
        $pdf->Cell(0,0,"Correo:");
        $pdf->SetFont('Arial','B',7);$pdf->setX(23);
        $pdf->Cell(0,0,$emailCliente);
        // $pdf->Cell(0,0,"FORMA DE PAGO");
/////////////////////////////


	$pdf->Output();
?>
