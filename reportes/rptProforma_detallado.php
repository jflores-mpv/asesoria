<?php

require('fpdf17/fpdf.php');
require('../clases/funciones.php');
require_once('../conexion.php');
session_start();
$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
$sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
$id_venta = $_GET["txtComprobanteNumero"];
// $id_venta = $_GET["id_venta"];
$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
$sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];


 $sql = "SELECT 
 
 ventas.`id_venta` AS ventas_id_venta, 
 ventas.`numero_factura_venta` AS ventas_numero_factura_venta, 
 ventas.`id_cliente` AS ventas_id_cliente, 
ventas.`fecha_venta` AS ventas_fecha_venta, 
ventas.`sub_total` AS ventas_sub_total, 
ventas.`id_iva` AS ventas_id_iva, 
ventas.`total` AS ventas_total,  
ventas.`sub0` AS ventas_sub0,  
ventas.`sub12` AS ventas_sub12,  
ventas.`descuento` AS ventas_descuento,  
ventas.`tipo_documento` AS ventas_tipo_documento, 
ventas.`descripcion` AS ventas_descripcion, 
clientes.`id_cliente` AS clientes_id_cliente, 
clientes.`nombre` AS clientes_nombre, 
clientes.`apellido` AS clientes_apellido, 
clientes.`direccion` AS clientes_direccion, 
clientes.`cedula` AS clientes_cedula, 
clientes.`numero_casa` AS clientes_numero_casa, 
clientes.`email` AS clientes_email, 
clientes.`telefono` AS clientes_telefono,
establecimientos.codigo as establecimientos_codigo, 
emision.codigo as emision_codigo , 
empresa.nombre as empresa_nombre, 
empresa.direccion as empresa_direccion,
empresa.contribuyente as empresa_contribuyente ,
empresa.Ocontabilidad as empresa_ocontabilidad, 
empresa.imagen as empresa_imagen , 
empresa.ruc as ruc ,
ventas.`sub12` AS ventas_sub_total12, 
ventas.`sub0` AS ventas_sub_total0, 
ventas.ClaveAcceso as ClaveAcceso , 
empresa.condicionesPago as condicionesPago,
empresa.formaPago as formaPago



FROM `clientes` clientes 
INNER JOIN `ventas` ventas ON clientes.`id_cliente` = ventas.`id_cliente` 
INNER JOIN establecimientos ON establecimientos.id= ventas.codigo_pun
INNER JOIN emision ON emision.id=ventas.codigo_lug
INNER JOIN empresa ON empresa.id_empresa=ventas.id_empresa " ;

$sql .= " where ventas.`id_empresa`='".$sesion_id_empresa."' ";
$sql .= " and ventas.`id_venta`='".$id_venta."' ";
$sql .= " order by clientes.`cedula`, ventas.`id_venta` "; 


// echo $sql;
$result = mysql_query($sql) or die(mysql_error());
    $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
    $numero = 0;

                //   echo $sql."***    ".$numero_filas;



    $data = array();
        while($row = mysql_fetch_array($result)) //for mayor    for($i=0; $i<$numero_filas; $i++)
        {
          $sub12=$row['ventas_sub12'];
          $sub0=$row['ventas_sub0'];
          $descuento=$row['ventas_descuento'];
          
          $observaciones = $row['ventas_descripcion'];

          $numero ++;

          $nombreEmpresa=  $row["empresa_nombre"];  
          $direccionEmpresa=  $row["empresa_direccion"];  
          $imagenEmpresa=  $row["empresa_imagen"]; 
          $contribuyenteEmpresa=  $row["empresa_contribuyente"];  
          $ocontabilidadEmpresa=  $row["empresa_ocontabilidad"];
           $ruc=  $row["ruc"];

          $subtotal12= $row['ventas_sub_total12'];
          $subtotal0= $row['ventas_sub_total0'];

            $condicionesEmpresa= $row['condicionesPago'];
            $formaPagoEmpresa= $row['formaPago'];


          $nombreCliente=  $row["clientes_nombre"];   
          $apellidoCliente=  $row["clientes_apellido"];
          $direccionCliente=  $row["clientes_direccion"];          
          $cedulaCliente=  $row["clientes_cedula"]; 
          $fechaVenta= $row['ventas_fecha_venta'];
          $telefonoCliente= $row['clientes_telefono'];
          $emailCliente= $row['clientes_email'];

          $numeroFactura= $row['ventas_numero_factura_venta'];

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

        // Cabecera de página
        function Header()
        {
          $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
          $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
          $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
          $id_venta = $_GET["txtComprobanteNumero"];

          $sql = "SELECT ventas.`id_venta` AS ventas_id_venta, ventas.`numero_factura_venta`
          AS ventas_numero_factura_venta, ventas.`id_cliente` AS ventas_id_cliente, ventas.`fecha_venta` AS ventas_fecha_venta,
          ventas.`sub_total` AS ventas_sub_total, ventas.`id_iva` AS ventas_id_iva, ventas.`total` AS ventas_total,  ventas.`sub0` AS ventas_sub0, 
          ventas.`sub12` AS ventas_sub12,  ventas.`descuento` AS ventas_descuento, ventas.`tipo_documento` AS ventas_tipo_documento, 
          clientes.`id_cliente` AS clientes_id_cliente, clientes.`nombre` AS clientes_nombre, clientes.`apellido` AS clientes_apellido,
          clientes.`direccion` AS clientes_direccion, clientes.`cedula` AS clientes_cedula, clientes.`numero_casa` AS clientes_numero_casa, 
          clientes.`email` AS clientes_email, clientes.`telefono` AS clientes_telefono,   establecimientos.codigo as establecimientos_codigo, 
          emision.codigo as emision_codigo , empresa.nombre as empresa_nombre, empresa.direccion as empresa_direccion, empresa.contribuyente
          as empresa_contribuyente ,empresa.Ocontabilidad as empresa_ocontabilidad, empresa.imagen as empresa_imagen ,  empresa.ruc as ruc , 
          empresa.email as emailEmpresa, empresa.telefono1 as telefonoEmpresa, condicionesPago as condicionesPago, formaPago as formaPago

          FROM `clientes` clientes 
          INNER JOIN `ventas` ventas ON clientes.`id_cliente` = ventas.`id_cliente` 
          INNER JOIN establecimientos ON establecimientos.id= ventas.codigo_pun
          INNER JOIN emision ON emision.id=ventas.codigo_lug 
          INNER JOIN empresa ON empresa.id_empresa=ventas.id_empresa " ;

          $sql .= " where ventas.`id_empresa`='".$sesion_id_empresa."' ";
          $sql .= " and ventas.`id_venta`='".$id_venta."' ";
          $sql .= " order by clientes.`cedula`, ventas.`id_venta` "; 
          $result = mysql_query($sql) or die(mysql_error());
    $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
    $numero = 0;

//    echo $sql."***    ".$numero_filas;
    $data = array();
        while($row = mysql_fetch_array($result)) //for mayor    for($i=0; $i<$numero_filas; $i++)
        {
            $numero ++;
            $tipoDocumento= $row['ventas_tipo_documento'];
            $nombreEmpresa=  $row["empresa_nombre"];  
            $direccionEmpresa=  $row["empresa_direccion"];  
            $imagenEmpresa=  $row["empresa_imagen"]; 
            $contribuyenteEmpresa=  $row["empresa_contribuyente"];  
            $ocontabilidadEmpresa=  $row["empresa_ocontabilidad"];
            $emailEmpresa=  $row["emailEmpresa"];  
            $telefonoEmpresa=  $row["telefonoEmpresa"];  
            $condicionesEmpresa= $row['condicionesPago'];
            $formaPagoEmpresa= $row['formaPago'];

$ruc= $row['ruc'];
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

       $numeroFactura= ceros($numeroFactura);
       $this->SetFont('Arial','B',8);

            // Movernos a la derecha
       $this->Cell(60);
        //  $this->Cell(80,10,'PEDIDO No.',1,0,'C');
       $this->Ln(20);


       $textypos = 5;
        // Agregamos los datos del cliente
       $this->SetFont('Arial','B',10);    
       $this->setY(30);$this->setX(135);
       
        $this->Cell(5,$textypos,"PROFORMA # $numeroFactura");
       
    
       $this->SetFont('Arial','',10);    
       $this->setY(35);$this->setX(135);
       $this->Cell(5,$textypos,"Fecha:");
       $this->SetFont('Arial','B',10);    
       $this->setY(35);$this->setX(155);
       $this->Cell(5,$textypos, $fechaVenta); 
       $this->SetFont('Arial','',10);   
       $this->setY(40);$this->setX(135);
        //   $this->Cell(5,$textypos,"Vencimiento");
       $this->SetFont('Arial','',10);   
       $this->setY(40);$this->setX(155);
        // $this->Cell(5,$textypos," 11/ENE/2020");


       $this->SetFont('Arial','B',20);  
       $this->setY(12);

       $this->setX(20);
       
// echo "IMAGEN==".$imagenEmpresa;
$imagenEmp= ($imagenEmpresa=='')?'blanco.png':$imagenEmpresa;
// // echo "IMAGEN DOS==".$imagenEmpresa;
$path= "../sql/archivos/$imagenEmp";
// // if (file_exists($path)) {
// //     echo "The file $path exists";
// // } else {
// //     echo "The file $path does not exist";
// // }
// // exit;
// // $this->Image("sql/archivos/LOGO05.png", 10, 10, 50 , 30, '', '', '', false, 300, '', false, false, 0, 'LB', false, false);
$this->Image($path, 24, 7, 44 , 27, '', '', '', false, 300, '', false, false, 0, 'LB', false, false);
//  $this->Image("../sql/archivos/".$imagenEmp, 5, 5, 50 , 30, '', '');
// $this->Image("archivos/".$imagenEmp, 15, 12, 80, 25, 'jpg', '', '', true, 150, '', false, false,0, false, false, false);

// echo "====../sql/archivos/".$imagenEmp;

       $this->setX(70);
// $this->Cell(5,$textypos,"REPORTE DE FACTURAS");
       $this->setX(10);
       $this->SetFont('Arial','B',10);    
       $this->setY(35);$this->setX(10);
// $this->Cell(5,$textypos, $nombreEmpresa);

       $this->MultiCell(70,$textypos, $nombreEmpresa,0,'C');
       $t1= $this->GetY();
       $this->SetFont('Arial','B',10);    
       $this->setY($t1);$this->setX(10);
       
       $this->MultiCell(70,$textypos,$direccionEmpresa,0,'C');
       $t1= $this->GetY();
       $this->setY($t1);$this->setX(10);
        $this->MultiCell(70,$textypos,"Telefono:  $telefonoEmpresa",0,'C');
           $this->setY($t1+5);$this->setX(10);
        $this->MultiCell(70,$textypos,"RUC:  $ruc",0,'C');
    //   $this->Cell(5,$textypos,"Telefono:  $telefonoCliente");
       $this->setY($t1+10);$this->setX(10);
         $this->MultiCell(70,$textypos,"Email : $emailEmpresa ",0,'C');
    //   $this->Cell(5,$textypos,"Email : $emailCliente ");
      $t1=$t1+15;
 $this->SetFont('Arial','B',10); 
       $this->setY($t1+5);$this->setX(10);
       $this->Cell(5,$textypos,utf8_decode("Enviar a :"));
     
       $this->SetFont('Arial','',10); 
       $this->setY($t1+10);$this->setX(10);
       $this->Cell(5,$textypos,utf8_decode("Razón social :"));
       $this->SetFont('Arial','B',10); 
       $this->setY($t1+10);$this->setX(35);
       $this->Cell(5,$textypos,$nombreCliente." ".$apellidoCliente);
       $this->SetFont('Arial','',10); 
       $this->setY($t1+15);$this->setX(10);
       $this->Cell(5,$textypos,"R.U.C/C.I.: ");
       $this->SetFont('Arial','B',10); 
       $this->setY($t1+15);$this->setX(35);
       $this->Cell(5,$textypos,$cedulaCliente);
       $this->SetFont('Arial','',10);
       $this->setY($t1+15);$this->setX(70);
      $this->Cell(5,$textypos,utf8_decode("Dirección: "));
      $this->SetFont('Arial','B',10);
      $this->setY($t1+15);$this->setX(95);
      $this->Cell(5,$textypos,$direccionCliente);
      
      $this->SetFont('Arial','',10);
       $this->setY($t1+20);$this->setX(10);
      $this->Cell(5,$textypos,utf8_decode("Telefono: "));
      $this->SetFont('Arial','B',10);
      $this->setY($t1+20);$this->setX(35);
      $this->Cell(5,$textypos,$telefonoCliente);
      
    $this->SetFont('Arial','',10);
      $this->setY($t1+20);$this->setX(70);
      $this->Cell(5,$textypos,utf8_decode("Correo: "));
      $this->SetFont('Arial','B',10);
      $this->setY($t1+20);$this->setX(95);
      $this->Cell(5,$textypos,$emailCliente);

// $this->setY();$this->setX(130);
// $this->Cell(5,$textypos,"Guia remision: ".$cedulaCliente);

       $iTabla= $this->GetY();

// Agregamos los datos del cliente
       $this->Ln(10);

     }
        // Pie de página
     function Footer()
     { 

    // Posición: a 1,5 cm del final
       $this->SetY(-15);
     // Arial italic 8
       $this->SetFont('Arial','I',8);
     // Número de página
       $this->Cell(0,10,utf8_decode('Página').$this->PageNo().'/{nb}',0,0,'C');

     }
   }



   $pdf = new PDF('P','mm','A4');  
// $pdf->SetAutoPageBreak(true ,50);
   $pdf->SetAutoPageBreak(true ,20);
   $pdf->AddPage();

   $pdf->SetFont('Arial','',10);
// $iTabla= $pdf->GetY();
/// Apartir de aqui empezamos con la tabla de productos
// $pdf->setY(70);$pdf->setX(135);
// $pdf->setY($iTabla+5);
   $pdf->setX(135);
   $pdf->Ln();
/////////////////////////////
//// Array de Cabecera
   $header = array("Cod.", "Producto","Cantidad","Valor Unitario","Total");


   $sql2="
   SELECT detalle_ventas.`id_detalle_venta`, detalle_ventas.`cantidad`, detalle_ventas.`estado`, detalle_ventas.`v_unitario`,
   detalle_ventas.`v_total`, detalle_ventas.`id_venta`, detalle_ventas.`id_servicio`, detalle_ventas.`tipo_venta`, productos.producto , productos.codigo

   FROM `detalle_ventas` 

   INNER JOIN productos ON productos.id_producto= detalle_ventas.id_servicio 

   WHERE detalle_ventas.`id_venta`='$id_venta'; ";
   $result2=mysql_query($sql2);
   $products = array();
   $contadorF=0;
// echo $sql2;
while($row2=mysql_fetch_array($result2))//permite ir de fila en fila de la tabla
{

  $products[$contadorF] = array($row2['codigo'], utf8_decode($row2['producto']) ,$row2['cantidad'],$row2['v_unitario'],$row2['v_total']);

  $contadorF++;
}

    // Column widths
$w = array(25, 90, 20, 25, 25);
    // Header
for($i=0;$i<count($header);$i++)
  $pdf->Cell($w[$i],7,$header[$i],1,0,'L');
$pdf->Ln();
    // Data
$total = 0;
foreach($products as $row)
{
  $cellWidth=95;
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

  $ancho = $line*6;
  $variable= 6;


  $pdf->Cell($w[0],$ancho,$row[0],1);
        // $pdf->Cell($w[1],6,$row[1],1);
  $y= $pdf->GetY();
  $pdf->MultiCell($w[1],$variable,utf8_decode($row[1]),1,'C');
  $y1= $pdf->GetY();
  $pdf->SetY($y);
  $pdf->SetX(125);
  $pdf->Cell($w[2],$ancho,number_format($row[2]),'1',0,'R');
  $pdf->Cell($w[3],$ancho,"$ ".number_format($row[3],4,".",","),'1',0,'R');
  $pdf->Cell($w[4],$ancho,"$ ".number_format($row[3]*$row[2],4,".",","),'1',0,'R');
  $pdf->SetY($y1);
        // $pdf->Ln();
  $total+=$row[3]*$row[2];
  $y2= $pdf->GetY();
}

/////////////////////////////
//// Apartir de aqui esta la tabla con los subtotales y totales
// $yposdinamic = 70 + (count($products)*10);
// $yposdinamic =$y2+2;
$yposdinamic = $pdf->GetY();
 //echo $yposdinamic;
// $pdf->Cell(5,20,"Informacion Adicional:");

$pdf->setY($yposdinamic);
// $y3= $pdf->GetY();
//  echo $y3;
$pdf->setX(235);
    // $pdf->Ln();

// echo "SUBTOTAL".$sub12."</br>";
/////////////////////////////
$header = array("", "");
$sqlDetalleV = "SELECT impuestos.id_iva, impuestos.codigo, impuestos.iva, detalle_ventas.`id_detalle_venta`, detalle_ventas.`idBodega`, detalle_ventas.`idBodegaInventario`, detalle_ventas.`cantidad`, detalle_ventas.`estado`, detalle_ventas.`v_unitario`, detalle_ventas.`descuento`, SUM(detalle_ventas.`v_total`) AS base_imponible, detalle_ventas.`id_venta`, detalle_ventas.`id_servicio`, detalle_ventas.`detalle`, detalle_ventas.`id_kardex`, detalle_ventas.`tipo_venta`, detalle_ventas.`id_empresa`,  detalle_ventas.`tarifa_iva`, SUM(detalle_ventas.`total_iva`) as suma_iva FROM `detalle_ventas` INNER JOIN impuestos ON impuestos.id_iva = detalle_ventas.tarifa_iva WHERE detalle_ventas.id_venta = '".$id_venta."'  GROUP BY impuestos.id_iva ";
	$resultDetVenta = mysql_query( $sqlDetalleV );
	 $array_ivas= array ();
	while($rowDetVent = mysql_fetch_array($resultDetVenta) ){
		$subT12 = $rowDetVent['base_imponible'];
		 $clave = $rowDetVent['tarifa_iva'] ;
		 $array_ivas[$clave][0]=$rowDetVent['iva'] ;  
		$array_ivas[$clave][1]=$rowDetVent['suma_iva'] ;  
        $data2[] = array('Subtotal '.$rowDetVent['iva'].' % :', number_format($subT12, 4 ,".",""));
	}
	$data2[] = array("Descuento", number_format($descuento,  4 ,".",""));
    foreach ($array_ivas as $key => $value) {
            //   echo "Key: $key, Value: $value\n";
            if($value[1]>0 ){
               
            	$data2[] = array("IVA ".$value[0]." %:", number_format($value[1],2));
            }
            
            } 



$data2[] = array("Total", $totalF);


if($sesion_id_empresa==41){
    // var_dump($data2);
    // exit;
}
    // print_r($data2);
    // exit();
    // Column widths
$w2 = array(25, 25);
    // Header

$pdf->Ln();
    // Data
$contadorFa=0;
foreach($data2 as $row)
{
  if( $contadorFa==0){
    $yposdinamic=  $pdf->GetY();
  }

  $contadorFa++;
  $pdf->setX(145);
  $pdf->Cell($w2[0],6,$row[0],1);
  $pdf->Cell($w2[1],6,"$ ".number_format($row[1],4, ".",","),'1',0,'R');

  $pdf->Ln();
}
// $yposdinamic=$yposdinamic-250;
// if($yposdinamic>200){
//     $yposdinamic=60;
// }
$yposdinamic = $pdf->GetY();
$yposdinamic= $yposdinamic-25;

$pdf->setY($yposdinamic+5);   $pdf->setX(30);
    // $pdf->Ln();
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,0,utf8_decode("Información adicional"));
$pdf->setY($yposdinamic+15);   
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,0,utf8_decode("Condiciones de pago:"));
$pdf->setY($yposdinamic+17); $pdf->setX(10);
$pdf->SetFont('Arial','B',10);
        // $pdf->Cell(0,0,"DIRECCION: $direccionCliente");
$pdf->MultiCell(105,5, $condicionesEmpresa,0,'J',0,10);
$pie= $pdf->GetY(); 
$pdf->setY($pie+2); 
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(105,5, 'Formas de Pago',0,'J',0,10);
$pdf->SetFont('Arial','B',10);
$arr = explode(',', $formaPagoEmpresa );
$res = array();
foreach($arr as $row) {
    $res[] = 
    $pdf->MultiCell(105,5, trim($row),0,'J',0,10);
}
$posNota = $pdf->GetY();
$pdf->setY($posNota+5);   
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,5,utf8_decode("Nota:"));
$pdf->setY($posNota+10); $pdf->setX(10);
$pdf->SetFont('Arial','B',10);
$pdf->MultiCell(105,5, $observaciones,0,'J',0,10);

$pdf->Output();
?>
