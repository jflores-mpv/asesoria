<?php


error_reporting(0);

require_once('../conexion.php');

 require "./pdf_mc_table.php";
 
session_start();
 $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
 $emision_codigo= $_SESSION['emision_codigo'];
  $establecimiento_codigo= $_SESSION['establecimiento_codigo'];

         $id = $_GET["id"];
        $sesion = $sesion_id_empresa;
         $numero_compra =  $_GET['checkCobrar'];
         $numero_compra = str_replace(",", "','", $numero_compra);
         $idDetalles =  $_GET['idDetalles'];
         $idDetalles = str_replace(",", "','", $idDetalles);
         
          $pagos =  $_GET['abonado'];
        $pagos = explode(",", $pagos);

function ceros($valor){
               $s='';
        for($i=1;$i<=9-strlen($valor);$i++)
                $s.="0";
        return $s.$valor;
    }

    $cuentaTipo=$_GET['switch-four'];
 $sqlEmpresa = "SELECT
                empresa.id_empresa empresa_id,
                empresa.ruc as empresa_ruc,
                empresa.razonSocial as empresa_razonSocial,
                empresa.contribuyente as empresa_contribuyente,
                empresa.direccion as empresa_direccion,
                empresa.Ocontabilidad as empresa_ocontabilidad,
                empresa.nombre as empresa_nombre,
                 empresa.telefono1 as empresa_telefono,
                 empresa.email  as empresa_correo
                FROM
                empresa where empresa.id_empresa ='".$sesion_id_empresa."' ";
$resultEmpresa = mysql_query($sqlEmpresa);
while($rowE = mysql_fetch_array($resultEmpresa)){
    $telefonoEmpresa = $rowE['empresa_telefono'];
        $correoEmpresa = $rowE['empresa_correo'];
        $direccionEmpresa=  $rowE["empresa_direccion"]; 
}
  $sqlCC="SELECT `id_cuenta_por_cobrar`, `tipo_documento`, `numero_factura`, `referencia`, `valor`, `saldo`, `numero_asiento`, `fecha_vencimiento`, `fecha_pago`, `id_proveedor`, `id_cliente`, `id_plan_cuenta`, `id_empresa`, `id_venta`, `estado`, `id_forma_pago`, `banco_referencia`, `documento_numero`, `id_empleado`, `cuotaAdmin`, `motivoDescuento`, `id_lead` FROM `cuentas_por_cobrar` WHERE id_cuenta_por_cobrar in('$numero_compra')";
      $resultCC = mysql_query($sqlCC);
      $numeroAbono='';
      $idVenta='';
      while($rowCC = mysql_fetch_array($resultCC)){
          $numeroAbono =$rowCC['numero_factura'];
          $fechaAbono = $rowCC['fecha_pago'];
          $idVenta = $rowCC['id_venta'];
      }
    class PDF extends PDF_MC_Table
    { 

        // Cabecera de página
        function Header()
        {
            $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
            $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
            $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
           $numero_compra =  $_GET['checkCobrar'];
         $numero_compra = str_replace(",", "','", $numero_compra);
                $sql = "SELECT
                empresa.id_empresa empresa_id,
                empresa.ruc as empresa_ruc,
                empresa.razonSocial as empresa_razonSocial,
                empresa.contribuyente as empresa_contribuyente,
                empresa.direccion as empresa_direccion,
                empresa.Ocontabilidad as empresa_ocontabilidad,
                empresa.nombre as empresa_nombre
                FROM
                empresa where empresa.id_empresa ='".$sesion_id_empresa."' ";
            $result = mysql_query($sql) ;
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
      }
      
      $sqlCC="SELECT `id_cuenta_por_cobrar`, `tipo_documento`, `numero_factura`, `referencia`, `valor`, `saldo`, `numero_asiento`, `fecha_vencimiento`, `fecha_pago`, `id_proveedor`, `id_cliente`, `id_plan_cuenta`, `id_empresa`, `id_venta`, `estado`, `id_forma_pago`, `banco_referencia`, `documento_numero`, `id_empleado`, `cuotaAdmin`, `motivoDescuento`, `id_lead` FROM `cuentas_por_cobrar` WHERE id_cuenta_por_cobrar in ('$numero_compra')";
      $resultCC = mysql_query($sqlCC);
      $numeroAbono='';
      while($rowCC = mysql_fetch_array($resultCC)){
          $numeroAbono =$rowCC['numero_factura'];
          $fechaAbono = $rowCC['fecha_pago'];
      }
      
      $numeroFactura= ceros($numeroFactura);
      $this->SetFont('Arial','B',8);
            // Movernos a la derecha
      $this->Cell(60);
        //  $this->Cell(80,10,'PEDIDO No.',1,0,'C');
      $this->Ln(20);
      $detalle ='';
      $textypos = 5;
// Agregamos los datos del cliente
      $this->SetFont('Arial','B',10);    
      $this->setY(30);$this->setX(135);
      $this->Cell(5,$textypos,"Factura # ".$numeroAbono);
      $this->SetFont('Arial','',10);    
      $this->setY(35);$this->setX(135);
      $this->Cell(5,$textypos,"Fecha:");
      $this->SetFont('Arial','B',10);    
      $this->setY(35);$this->setX(149);
      $this->Cell(5,$textypos, $fechaAbono);  
      $this->SetFont('Arial','',10);    
     
      $this->SetFont('Arial','',10);   
      $this->setY(40);$this->setX(155);
// $this->Cell(5,$textypos," 11/ENE/2020");


      $this->SetFont('Arial','B',20);  
      $this->setY(12);

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
      $obc=($ocontabilidadEmpresa==1)?"SI":"NO";
      $this->Cell(5,$textypos,"OBLIGADO A LLEVAR CONTABILIDAD: $obc ");

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

      $iTabla= $this->GetY();

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
 if ($cuentaTipo=='2'){
       
       $sqlDetalle= " SELECT detalle_cuentas_por_cobrar.fecha_pago, detalle_cuentas_por_cobrar.valor as cantidadCancelada,  cuentas_por_cobrar.* , clientes.nombre as name, clientes.cedula as identificacion, empresa.imagen, emision.codigo as emision_codigo,
    establecimientos.codigo as establecimientos_codigo ,
    ventas.numero_factura_venta as numero_factura_venta, formas_pago.nombre
      FROM `clientes` clientes 
    INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON clientes.`id_cliente` = cuentas_por_cobrar.`id_cliente`
       INNER JOIN empresa ON empresa.id_empresa= cuentas_por_cobrar.id_empresa
       INNER JOIN detalle_cuentas_por_cobrar ON detalle_cuentas_por_cobrar.id_cuenta_por_cobrar = cuentas_por_cobrar.id_cuenta_por_cobrar
       LEFT JOIN ventas ON ventas.id_venta = cuentas_por_cobrar.id_venta
       LEFT JOIN emision ON ventas.codigo_lug = emision.id
        LEFT JOIN establecimientos ON establecimientos.id= ventas.codigo_pun
         LEFT JOIN formas_pago ON formas_pago.id_forma_pago = detalle_cuentas_por_cobrar.id_forma_pago
       where  cuentas_por_cobrar.id_empresa='".$sesion."' and cuentas_por_cobrar.id_cliente='".$id."' ";
       
    }else if ($cuentaTipo=='1'){
         
         $sqlDetalle= " SELECT detalle_cuentas_por_cobrar.fecha_pago, detalle_cuentas_por_cobrar.valor as cantidadCancelada, cuentas_por_cobrar.* , proveedores.nombre_comercial as name, proveedores.ruc as identificacion, empresa.imagen , formas_pago.nombre
       FROM `proveedores` proveedores 
    INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON proveedores.`id_proveedor` = cuentas_por_cobrar.`id_proveedor`
       INNER JOIN empresa ON empresa.id_empresa= cuentas_por_cobrar.id_empresa
       INNER JOIN detalle_cuentas_por_cobrar ON detalle_cuentas_por_cobrar.id_cuenta_por_cobrar = cuentas_por_cobrar.id_cuenta_por_cobrar
      LEFT JOIN formas_pago ON formas_pago.id_forma_pago = detalle_cuentas_por_cobrar.id_forma_pago
       where  cuentas_por_cobrar.id_empresa='".$sesion."' and cuentas_por_cobrar.id_proveedor='".$id."' ";
       
    }else if ($cuentaTipo=='3'){
         
        $sqlDetalle= " SELECT detalle_cuentas_por_cobrar.fecha_pago, detalle_cuentas_por_cobrar.valor as cantidadCancelada, cuentas_por_cobrar.* ,  name, identificacion, empresa.imagen , formas_pago.nombre
       from cuentas_por_cobrar INNER JOIN leads ON leads.id = cuentas_por_cobrar.id_lead
       INNER JOIN empresa ON empresa.id_empresa= cuentas_por_cobrar.id_empresa
       INNER JOIN detalle_cuentas_por_cobrar ON detalle_cuentas_por_cobrar.id_cuenta_por_cobrar = cuentas_por_cobrar.id_cuenta_por_cobrar
      LEFT JOIN formas_pago ON formas_pago.id_forma_pago = detalle_cuentas_por_cobrar.id_forma_pago
       where  cuentas_por_cobrar.id_empresa='".$sesion."' and cuentas_por_cobrar.id_lead='".$id."' ";
       
    }else if ($cuentaTipo=='4'){
         
         $sqlDetalle= " SELECT detalle_cuentas_por_cobrar.fecha_pago, detalle_cuentas_por_cobrar.valor as cantidadCancelada, cuentas_por_cobrar.* ,empleados.nombre as name, empleados.cedula as identificacion, empresa.imagen , formas_pago.nombre
       FROM  `empleados` empleados
    INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON empleados.`id_empleado` = cuentas_por_cobrar.`id_empleado` 
       INNER JOIN empresa ON empresa.id_empresa= cuentas_por_cobrar.id_empresa
       INNER JOIN detalle_cuentas_por_cobrar ON detalle_cuentas_por_cobrar.id_cuenta_por_cobrar = cuentas_por_cobrar.id_cuenta_por_cobrar
       LEFT JOIN formas_pago ON formas_pago.id_forma_pago = detalle_cuentas_por_cobrar.id_forma_pago
       where  cuentas_por_cobrar.id_empresa='".$sesion."' and cuentas_por_cobrar.id_empleado='".$id."' ";
    }
if(trim($idVenta)!=''){
    $sqlDetalle.= " and cuentas_por_cobrar.id_venta='".$idVenta."' ";
}else{
   $sqlDetalle.= " and cuentas_por_cobrar.id_cuenta_por_cobrar in ('".$numero_compra."') ";  
}
 
//  if($numero_compra!='0'){
//     $sqlDetalle.= " and detalle_cuentas_por_cobrar.id_detalle_cuentas_por_cobrar in('".$idDetalles."' )  " ;
//     }
// echo $sqlDetalle;
$resultDetalleVenta=mysql_query( $sqlDetalle);
if($sesion==116){
    // echo $sqlDetalle;
}
$data2=array();
 $data2[0][]='FORMAS DE PAGO';
 $data2[0][]='FECHA DE PAGO';
 $data2[0][]='# FACTURA';
 $data2[0][]='CONCEPTO';
 $data2[0][]='REFERENCIA';
 $data2[0][]='VALOR';
 $data2[0][]='SALDO';
      
    
$suma=0;

$z= 1;

$pdf->SetWidths(array(40,22,22,25,35,30,30));
$pdf->SetLineHeight(5);
$totalF=0;
$numeroAbono=0;
$idAnterior='';
$costo=0;
    while($ODetalleVenta=mysql_fetch_array($resultDetalleVenta)){
        
        if($ODetalleVenta['id_cuenta_por_cobrar']!=$idAnterior){
            $numeroAbono++;
            $idAnterior=$ODetalleVenta['id_cuenta_por_cobrar'];
            $costo=$ODetalleVenta['valor'];
        }

        $data2[$z][]=$ODetalleVenta["nombre"];
        $data2[$z][]=$ODetalleVenta["fecha_pago"];
        if($cuentaTipo=='2'){
             $data2[$z][]=$ODetalleVenta['establecimientos_codigo']."--".$ODetalleVenta['emision_codigo']."--".ceros($ODetalleVenta['numero_factura_venta']);
        }else{
             $data2[$z][]=ceros($ODetalleVenta['numero_factura']);
        }
   
        $data2[$z][]='ABONO # '.$numeroAbono;
        $data2[$z][]=$ODetalleVenta["referencia"];
        $data2[$z][]=number_format($ODetalleVenta["cantidadCancelada"], 2, '.', ' ');
        $data2[$z][]=number_format($costo-$ODetalleVenta["cantidadCancelada"], 2, '.', ' ');
        $totalF = $totalF+$ODetalleVenta["cantidadCancelada"];
        $z++;
        $costo=$costo-$ODetalleVenta["cantidadCancelada"];
    }

    $d=0;
   
    foreach($data2 as $item){

      $pdf->Row(array(
        $item[0],
        $item[1],
        $item[2],
        $item[3],
        $item[4],
        $item[5],
        $item[6],
      ));

      $d++;
    }





$header = array("", "");
$data3 = array(
    // array("Subtotal 12%", number_format($sumaSub12, 4 ,".","")),
    // array("Subtotal 0%", number_format($sumaSub0, 4 ,".","")),
    // array("Descuento", number_format($descuento,  4 ,".",",")),
    // array("IVA",  number_format(0,  4 ,".",",") ),
      array("Total cancelado",number_format($totalF, 4 ,".","")   ),

);

    // Column widths
$w2 = array(30, 25);
    // Header

$pdf->Ln();
$contadorFa=0;
$pdf->SetFont('Arial','',9);
foreach($data3 as $row)
{
    if( $contadorFa==0){
        $yposdinamic=  $pdf->GetY();
    }

    $contadorFa++;

    $pdf->setX(145);
    $pdf->Cell($w2[0],6,$row[0],1);
    $pdf->Cell($w2[1],6,"$ ".number_format($row[1],4, ".",""),'1',0,'R');

    $pdf->Ln();
}

$yposdinamic = $pdf->GetY();

 $posY= $pdf->GetY()+20;
    $pdf->Line(40,$posY,90,$posY);
    $pdf->Line(125,$posY,175,$posY);
      $pdf->SetFont('Arial','B',7);
    
    $pdf->SetY($posY+2);
    $pdf->SetX(5);
     $pdf->Cell(75,5,'FIRMA ENTREGADO',0,0,'R');
         $pdf->Cell(7,5,'',0,0,'R');
      $pdf->Cell(75,5,'FIRMA RECIBIDO',0,0,'R');
$pdf->Output();

?>
