<?php
error_reporting(0);
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
$tipo = $_GET['tipo'];


if($tipo=='Ingresos'){
    $sql = "SELECT
    empresa.id_empresa empresa_id,
    empresa.ruc as empresa_ruc,
    empresa.razonSocial as empresa_razonSocial,
    empresa.contribuyente as empresa_contribuyente,
    empresa.direccion as empresa_direccion,
    empresa.Ocontabilidad as empresa_ocontabilidad,
    empresa.nombre as empresa_nombre,
    empresa.telefono1 as empresa_telefono,
    empresa.email  as empresa_correo,
    ingresos.id_ingreso,
    ingresos.fecha,
    ingresos.total as ingreso_total ,
    ingresos.sub_total as ingreso_subtotal,
    ingresos.numero as ingreso_numero,
    impuestos.iva
    FROM
    ingresos
    INNER JOIN empresa ON empresa.id_empresa = ingresos.id_empresa
    INNER JOIN impuestos ON ingresos.id_iva = impuestos.id_iva" ;
    
    $sql .= " where ingresos.`id_empresa`='".$sesion_id_empresa."' ";
    $sql .= " and ingresos.`id_ingreso`='".$id_venta."' ";
    $sql .= " order by ingresos.`id_ingreso`  "; 
}else if($tipo=='Egresos'){
    $sql = "SELECT
    empresa.id_empresa empresa_id,
    empresa.ruc as empresa_ruc,
    empresa.razonSocial as empresa_razonSocial,
    empresa.contribuyente as empresa_contribuyente,
    empresa.direccion as empresa_direccion,
    empresa.Ocontabilidad as empresa_ocontabilidad,
    empresa.nombre as empresa_nombre,
    empresa.telefono1 as empresa_telefono,
    empresa.email  as empresa_correo,
    egresos.id_egreso,
    egresos.fecha,
    egresos.total as ingreso_total ,
    egresos.sub_total as ingreso_subtotal,
    egresos.numero as ingreso_numero,
    impuestos.iva
    FROM
    egresos
    INNER JOIN empresa ON empresa.id_empresa = egresos.id_empresa
    INNER JOIN impuestos ON egresos.id_iva = impuestos.id_iva" ;
    
    $sql .= " where egresos.`id_empresa`='".$sesion_id_empresa."' ";
    $sql .= " and egresos.`id_egreso`='".$id_venta."' ";
    $sql .= " order by egresos.`id_egreso`  "; 
}else if($tipo=='Transferencias'){
    $sql = "SELECT
    empresa.id_empresa empresa_id,
    empresa.ruc as empresa_ruc,
    empresa.razonSocial as empresa_razonSocial,
    empresa.contribuyente as empresa_contribuyente,
    empresa.direccion as empresa_direccion,
    empresa.Ocontabilidad as empresa_ocontabilidad,
    empresa.nombre as empresa_nombre,
    empresa.telefono1 as empresa_telefono,
    empresa.email  as empresa_correo,
    transferencias.id_transferencia,
    transferencias.fecha_trans,
    egresos.total as ingreso_total ,
    egresos.sub_total as ingreso_subtotal,
    transferencias.num_trans as ingreso_numero,
    impuestos.iva
    FROM
    transferencias
    INNER JOIN empresa ON empresa.id_empresa = transferencias.id_empresa
    INNER JOIN egresos ON egresos.id_egreso = transferencias.id_egreso
  
    INNER JOIN impuestos ON egresos.id_iva = impuestos.id_iva" ;
    
    $sql .= " where transferencias.`id_empresa`='".$sesion_id_empresa."' ";
    $sql .= " and transferencias.`id_transferencia`='".$id_venta."' ";
    $sql .= " order by transferencias.`id_transferencia`  "; 

}

$result = mysql_query($sql) or die(mysql_error());
    $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
    $numero = 0;

                //    echo $sql."***    ".$numero_filas;

    $data = array();
        while($row = mysql_fetch_array($result)) //for mayor    for($i=0; $i<$numero_filas; $i++)
        {
        $numero ++;
        $iva = $row['iva'];
        $telefonoEmpresa = $row['empresa_telefono'];
        $correoEmpresa = $row['empresa_correo'];
        $direccionEmpresa=  $row["empresa_direccion"]; 
        $subtotalF= $row['ingreso_subtotal'];
        $totalF= $row['ingreso_total'];
 
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
            $tipo = $_GET['tipo'];
            if($tipo=='Ingresos'){
                $sql = "SELECT
                empresa.id_empresa empresa_id,
                empresa.ruc as empresa_ruc,
                empresa.razonSocial as empresa_razonSocial,
                empresa.contribuyente as empresa_contribuyente,
                empresa.direccion as empresa_direccion,
                empresa.Ocontabilidad as empresa_ocontabilidad,
                empresa.nombre as empresa_nombre,
                ingresos.id_ingreso as ingreso_id,
                ingresos.fecha as ingreso_fecha,
                ingresos.total as ingreso_total,
                ingresos.sub_total as ingreso_subtotal,
                ingresos.numero as ingreso_numero,
               bodegas.detalle as bodegas_nombre
                FROM
                ingresos
                INNER JOIN detalle_ingresos ON detalle_ingresos.id_ingreso = ingresos.id_ingreso
                INNER JOIN bodegas ON bodegas.id = detalle_ingresos.bodega
                INNER JOIN empresa ON empresa.id_empresa = ingresos.id_empresa" ;
    
             $sql .= " where ingresos.`id_empresa`='".$sesion_id_empresa."' ";
             $sql .= " and ingresos.`id_ingreso`='".$id_venta."' ";
             $sql .= " order by ingresos.`id_ingreso`  "; 
            }else if($tipo=='Egresos'){
                $sql = "SELECT
                empresa.id_empresa empresa_id,
                empresa.ruc as empresa_ruc,
                empresa.razonSocial as empresa_razonSocial,
                empresa.contribuyente as empresa_contribuyente,
                empresa.direccion as empresa_direccion,
                empresa.Ocontabilidad as empresa_ocontabilidad,
                empresa.nombre as empresa_nombre,
                empresa.telefono1 as empresa_telefono,
                empresa.email  as empresa_correo,
                egresos.id_egreso as ingreso_id,
                egresos.fecha as ingreso_fecha,
                egresos.total as ingreso_total ,
                egresos.sub_total as ingreso_subtotal,
                egresos.numero as ingreso_numero,
                bodegas.detalle as bodegas_nombre
                FROM
                egresos
                INNER JOIN empresa ON empresa.id_empresa = egresos.id_empresa
                INNER JOIN detalle_egresos ON detalle_egresos.id_egreso = egresos.id_egreso
                INNER JOIN bodegas ON bodegas.id = detalle_egresos.bodega ";
                 
                $sql .= " where egresos.`id_empresa`='".$sesion_id_empresa."' ";
                $sql .= " and egresos.`id_egreso`='".$id_venta."' ";
                $sql .= " order by egresos.`id_egreso`  "; 
            }else if($tipo=='Transferencias'){
                $sql = "SELECT
                empresa.id_empresa empresa_id,
                empresa.ruc as empresa_ruc,
                empresa.razonSocial as empresa_razonSocial,
                empresa.contribuyente as empresa_contribuyente,
                empresa.direccion as empresa_direccion,
                empresa.Ocontabilidad as empresa_ocontabilidad,
                empresa.nombre as empresa_nombre,
                empresa.telefono1 as empresa_telefono,
                empresa.email  as empresa_correo,
                transferencias.id_transferencia as ingreso_id,
                transferencias.fecha_trans as ingreso_fecha,
                ingresos.total as ingreso_total ,
                ingresos.sub_total as ingreso_subtotal,
                transferencias.num_trans as ingreso_numero,
                bodegas.detalle as bodegas_nombre
                FROM
                transferencias
                INNER JOIN empresa ON empresa.id_empresa = transferencias.id_empresa
                INNER JOIN ingresos ON ingresos.id_ingreso = transferencias.id_ingreso
                INNER JOIN detalle_ingresos ON detalle_ingresos.id_ingreso = ingresos.id_ingreso

                INNER JOIN bodegas ON bodegas.id = detalle_ingresos.bodega " ;
                
                $sql .= " where transferencias.`id_empresa`='".$sesion_id_empresa."' ";
                $sql .= " and transferencias.`id_transferencia`='".$id_venta."' ";
                $sql .= " order by transferencias.`id_transferencia`  "; 
            }
           
            $result = mysql_query($sql) or die(mysql_error());
    $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
    $numero = 0;

//    echo $sql."***    ".$numero_filas;
    $data = array();
        while($row = mysql_fetch_array($result)) //for mayor    for($i=0; $i<$numero_filas; $i++)
        {
           $numero ++;

           $nombreEmpresa=  $row["empresa_nombre"];  
           $direccionEmpresa=  $row["empresa_direccion"];  
           $imagenEmpresa=  $row["empresa_imagen"]; 
           $contribuyenteEmpresa=  $row["empresa_contribuyente"];  
           $ocontabilidadEmpresa=  $row["empresa_ocontabilidad"];
            $razonSocialEmpresa= $row["empresa_razonSocial"];  
            $empresaRuc = $row["empresa_ruc"]; 
          $nombreBodega = $row['bodegas_nombre'];

           
           $subtotalF= $row['ingreso_subtotal'];
           $totalF= $row['ingreso_total'];
           $numeroIngreso= $row['ingreso_numero'];
            $fechaIngreso = $row['ingreso_fecha'];

       }

        // echo "==>2==>".$subtotalF."<==";

       $numeroFactura= ceros($numeroFactura);
       $this->SetFont('Arial','B',8);

            // Movernos a la derecha
       $this->Cell(60);
        //  $this->Cell(80,10,'PEDIDO No.',1,0,'C');
       $this->Ln(20);

       $detalle ='';
       if($tipo=='Ingresos'){
                $sqlT = "SELECT id_transferencia FROM `transferencias` where id_ingreso=$id_venta";
        $resultT = mysql_query($sqlT);
        $existeTransferencia = mysql_num_rows($resultT);

        if($existeTransferencia >0){
            $detalle = 'Transferencia';
        }else{
            $sql = "SELECT detalle FROM `kardes` where id_empresa=$sesion_id_empresa and (detalle='Ingreso'|| detalle='Saldo Inicial') and id_factura=$id_venta";
        $result = mysql_query($sql);

        while($row = mysql_fetch_array($result)){
            $detalle = $row['detalle'];
        }
            $detalle = ($detalle=='Ingreso')?'':$detalle;
        }

       }else if($tipo=='Egresos'){
        $sqlT = "SELECT id_transferencia FROM `transferencias` where id_egreso=$id_venta";
        $resultT = mysql_query($sqlT);
        $existeTransferencia = mysql_num_rows($resultT);
        
        if($existeTransferencia >0){
            $detalle = 'Transferencia';
        }else{
            $sql = "SELECT detalle FROM `kardes` where id_empresa=$sesion_id_empresa and (detalle='Egreso'|| detalle='Saldo Inicial') and id_factura=$id_venta";
        $result = mysql_query($sql);
        
        while($row = mysql_fetch_array($result)){
            $detalle = $row['detalle'];
        }
            $detalle = ($detalle=='Egreso')?'':$detalle;
        }

       }

       $textypos = 5;
// Agregamos los datos del cliente
       $this->SetFont('Arial','B',10);    
       $this->setY(30);$this->setX(135);
       $this->Cell(5,$textypos,$tipo." ".$detalle." # ".$numeroIngreso);
       $this->SetFont('Arial','',10);    
       $this->setY(35);$this->setX(135);
       $this->Cell(5,$textypos,"Fecha:");
       $this->SetFont('Arial','B',10);    
       $this->setY(35);$this->setX(155);
       $this->Cell(5,$textypos, $fechaIngreso);  
       $this->SetFont('Arial','',10);    
       $this->setY(40);$this->setX(135);
       $this->Cell(5,$textypos,"BODEGA:");
       $this->setY(40);$this->setX(155);
       $this->SetFont('Arial','B',10);  
       $this->Cell(5,$textypos,$nombreBodega);
       $this->SetFont('Arial','',10);   
       $this->setY(40);$this->setX(155);
// $this->Cell(5,$textypos," 11/ENE/2020");


       $this->SetFont('Arial','B',20);  
       $this->setY(12);

       $this->setX(10);
// echo "IMAGEN==".$imagenEmpresa;
// $imagenEmp= ($imagenEmpresa=='')?'blanco.png':$imagenEmpresa;
// // echo "IMAGEN DOS==".$imagenEmpresa;
// // $path= '../sql/archivos/LOGO05.png';
// // if (file_exists($path)) {
// //     echo "The file $path exists";
// // } else {
// //     echo "The file $path does not exist";
// // }
// // exit;
// // $this->Image("sql/archivos/LOGO05.png", 10, 10, 50 , 30, '', '', '', false, 300, '', false, false, 0, 'LB', false, false);
// // $this->Image("../sql/archivos/".$imagenEmp, 10, 10, 50 , 30, '', '', '', false, 300, '', false, false, 0, 'LB', false, false);
// //  $this->Image("../sql/archivos/".$imagenEmp, 5, 5, 50 , 30, '', '');
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
// $this->Cell(5,$textypos,$direccionEmpresa);
       $this->MultiCell(70,$textypos,$direccionEmpresa,0,'C');
       $t1= $this->GetY();
       $this->setY($t1);$this->setX(10);
       $this->Cell(5,$textypos,"Contribuyente Especial Nro:  $contribuyenteEmpresa");
       $this->setY($t1+5);$this->setX(10);
       $this->Cell(5,$textypos,"OBLIGADO A LLEVAR CONTABILIDAD: $ocontabilidadEmpresa ");

       $this->SetFont('Arial','',10); 
       $this->setY($t1+10);$this->setX(10);
       $this->Cell(5,$textypos,utf8_decode("Razón social :"));
       $this->SetFont('Arial','B',10); 
       $this->setY($t1+10);$this->setX(35);
       $this->Cell(5,$textypos,$razonSocialEmpresa);
       $this->SetFont('Arial','',10); 
       $this->setY($t1+15);$this->setX(10);
       $this->Cell(5,$textypos,"R.U.C/C.I.: ");
       $this->SetFont('Arial','B',10); 
       $this->setY($t1+15);$this->setX(35);
       $this->Cell(5,$textypos,$empresaRuc);
       $this->SetFont('Arial','',10);
       $this->setY($t1+15);$this->setX(70);
       $this->Cell(5,$textypos,utf8_decode("Fecha emisión: "));
       $this->SetFont('Arial','B',10);
       $this->setY($t1+15);$this->setX(95);
       $this->Cell(5,$textypos,date('Y-m-d'));

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


if($tipo=='Ingresos'){
    $header = array("Cod.", "Producto","Cantidad","Valor Unitario","Total");
    $w = array(20, 95, 20, 25, 25);
    $sql2="SELECT
  
    ingresos.total as ingreso_total ,
    ingresos.sub_total as ingreso_subtotal,
    ingresos.numero as ingreso_numero,
    detalle_ingresos.id_detalle_ingreso,
    detalle_ingresos.cantidad as detalle_cantidad,
    detalle_ingresos.bodega,
    detalle_ingresos.v_unitario as detalle_valorUnitario,
    detalle_ingresos.v_total as detalle_valorTotal,
    productos.producto as producto_nombre,
    productos.grupo as producto_codigo,
 productos.codigo as producto_codigo_codigo,
     productos.iva as producto_iva
    FROM
    ingresos
    INNER JOIN detalle_ingresos ON detalle_ingresos.id_ingreso = ingresos.id_ingreso
    INNER JOIN productos ON productos.id_producto = detalle_ingresos.id_producto 
    
    WHERE ingresos.`id_ingreso`='$id_venta'; ";
}else if($tipo=='Egresos'){
    $header = array("Cod.", "Producto","Cantidad","Valor Unitario","Total");
    $w = array(20, 95, 20, 25, 25);
    $sql2="SELECT
    egresos.total as ingreso_total ,
    egresos.sub_total as ingreso_subtotal,
    egresos.numero as ingreso_numero,
    detalle_egresos.id_detalle_egreso,
    detalle_egresos.cantidad as detalle_cantidad,
    detalle_egresos.bodega,
    detalle_egresos.v_unitario as detalle_valorUnitario,
    detalle_egresos.v_total as detalle_valorTotal,
    productos.producto as producto_nombre,
    productos.grupo as producto_codigo,
     productos.iva as producto_iva
    FROM
    egresos
    INNER JOIN detalle_egresos ON detalle_egresos.id_egreso = egresos.id_egreso
    INNER JOIN productos ON productos.id_producto = detalle_egresos.id_producto 
    
    WHERE egresos.`id_egreso`='$id_venta'; ";
}else if($tipo=='Transferencias'){
    $header = array("Cod.", "Producto","Bodega Origen","Cantidad","Valor Unitario","Total");
    $w = array(20, 75,30, 20, 25, 25);
    $sql2 = "SELECT

    transferencias.num_trans as ingreso_numero,
     egresos.total as ingreso_total ,
    egresos.sub_total as ingreso_subtotal,
    detalle_egresos.id_detalle_egreso,
    detalle_egresos.cantidad as detalle_cantidad,
    detalle_egresos.v_unitario as detalle_valorUnitario,
    detalle_egresos.v_total as detalle_valorTotal,
     productos.producto as producto_nombre,
     productos.grupo as producto_codigo,
     productos.iva as producto_iva,
     bodegas.detalle as bodegas_nombre
    FROM
    transferencias
    INNER JOIN egresos ON egresos.id_egreso = transferencias.id_egreso
    INNER JOIN detalle_egresos ON detalle_egresos.id_egreso = egresos.id_egreso 
    INNER JOIN bodegas ON bodegas.id = detalle_egresos.bodega
    INNER JOIN productos ON productos.id_producto = detalle_egresos.id_producto 
     WHERE transferencias.`id_transferencia`='".$id_venta."' ";
}

$result2=mysql_query($sql2);
$products = array();
$contadorF=0;
$sumaSub12=0;
$sumaSub0=0;
while($row2=mysql_fetch_array($result2))//permite ir de fila en fila de la tabla
{
     if($tipo=='Egresos' || $tipo=='Ingresos'){
          $products[$contadorF] = array($row2['producto_codigo_codigo'], utf8_decode($row2['producto_nombre']),$row2['detalle_cantidad'],$row2['detalle_valorUnitario'],$row2['detalle_valorTotal']);
     }else{
          $products[$contadorF] = array($row2['producto_codigo'], utf8_decode($row2['producto_nombre']),$row2['bodegas_nombre'] ,$row2['detalle_cantidad'],$row2['detalle_valorUnitario'],$row2['detalle_valorTotal']);
     }
    
    if($row['iva']=='si'){
        $sumaSub12= $sumaSub12+$row2['detalle_valorTotal'];
    }else{
          $sumaSub0=$sumaSub0+$row2['detalle_valorTotal'];
    }
    
    

    $contadorF++;
}

    // Column widths

    // Header
for($i=0;$i<count($header);$i++)
    $pdf->Cell($w[$i],7,$header[$i],1,0,'C');
$pdf->Ln();
    // Data
$total = 0;
foreach($products as $row)
{
    $cellWidth=($tipo =='Transferencias' )?90:95;
    $cellHeight=5;
    // $row[1]='12345678901234567890123456789012345678901234567';
    // echo $pdf->GetStringWidth($row[1]);exit;
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

if($tipo =='Transferencias' ){
    $pdf->SetFont('Arial','',8);
    // $pdf->SetFont('Arial','',10);
    $cellWidth=27;
            $cellHeight=5;
            // $row[2]='123456789012345678';
            
    if($pdf->GetStringWidth($row[2])<$cellWidth){
        $line2=1;
    }else{
        $line2=2;
    }
// echo $line2; exit;
    $ancho2 = $line2*6;

   $variable= ($line>1)?6: 12;
    $variable2= ($line2>1)?6: 12;
    
    $ancho =6;

        $pdf->Cell($w[0],$ancho,$row[0],1);
        // $pdf->Cell($w[1],6,$row[1],1);
    $y= $pdf->GetY();
 
    $pdf->MultiCell($w[1],6,substr(utf8_decode($row[1]), 0, 45),1,'C');
    $y1= $pdf->GetY();
    $pdf->SetY($y);
    $pdf->SetX(105);
    $pdf->MultiCell($w[2],6,substr(utf8_decode($row[2]), 0, 16),1,'C');
    $pdf->SetY($y);
    $pdf->SetX(135);
    $pdf->Cell($w[3],$ancho,number_format($row[3]),'1',0,'R');
    $pdf->Cell($w[4],$ancho,"$ ".number_format($row[4],4,".",""),'1',0,'R');
    $pdf->Cell($w[5],$ancho,"$ ".number_format($row[4]*$row[3],4,".",""),'1',0,'R');
    $pdf->SetY($y1);
        // $pdf->Ln();
    $total+=$row[3]*$row[4];
    $y2= $pdf->GetY();
}else{
    $ancho=6;
        $pdf->Cell($w[0],$ancho,$row[0],1);
        // $pdf->Cell($w[1],6,$row[1],1);
    $y= $pdf->GetY();

    $pdf->MultiCell($w[1],$variable,substr(utf8_decode($row[1]), 0, 50),1,'C');
    $y1= $pdf->GetY();
    $pdf->SetY($y);
    $pdf->SetX(125);
    $pdf->Cell($w[2],$ancho,number_format($row[2]),'1',0,'R');
    $pdf->Cell($w[3],$ancho,"$ ".number_format($row[3],4,".",""),'1',0,'R');
    $pdf->Cell($w[4],$ancho,"$ ".number_format($row[3]*$row[2],4,".",""),'1',0,'R');
    $pdf->SetY($y1);
        // $pdf->Ln();
    $total+=$row[3]*$row[2];
    $y2= $pdf->GetY();
}
 
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


    // $pdf->Ln();

// $totalIva = ($totalF*$iva)/100;
// $descuento = 0;
// $sub0 = 0;
// $sub12 = $totalF-$totalIva;
/////////////////////////////
$header = array("", "");
$data2 = array(
    array("Subtotal 12%", number_format($sumaSub12, 4 ,".","")),
    array("Subtotal 0%", number_format($sumaSub0, 4 ,".","")),
    // array("Descuento", number_format($descuento,  4 ,".",",")),
    // array("IVA",  number_format(0,  4 ,".",",") ),
      array("Total",number_format($totalF, 4 ,".","")   ),

);
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
    if($tipo=='Transferencias'){
        $pdf->setX(155);
    }else{
        $pdf->setX(145);
    }
    $pdf->Cell($w2[0],6,$row[0],1);
    $pdf->Cell($w2[1],6,"$ ".number_format($row[1],4, ".",""),'1',0,'R');

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
$pdf->Cell(0,0,utf8_decode("Telefóno:"));
$pdf->setY($yposdinamic+13); $pdf->setX(30);
$pdf->SetFont('Arial','B',10);
        // $pdf->Cell(0,0,"DIRECCION: $direccionCliente");
$pdf->MultiCell(105,5, $telefonoEmpresa,0,'J',0,10);
$pie= $pdf->GetY(); 
$pdf->setY($pie+2); 
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,0,utf8_decode("Correo:"));
$pdf->setX(30);
$pdf->SetFont('Arial','B',10);
$pdf->MultiCell(105,5, $correoEmpresa,0,'J',0,10);


$pdf->Output();
?>
