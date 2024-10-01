<?php
error_reporting(0);
   session_start();
	# Incluyendo librerias necesarias #
    date_default_timezone_set('America/Guayaquil');
	require_once('../conexion.php');
 require "./pdf_mc_table.php";
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
         $id = $_GET["id"];
        $sesion = $sesion_id_empresa;
         $numero_compra =  $_GET['checkCobrar'];
         $numero_compra = str_replace(",", "','", $numero_compra);
         $idDetalles =  $_GET['idDetalles'];
         $idDetalles = str_replace(",", "','", $idDetalles);
         
          $pagos =  $_GET['abonado'];
        $pagos = explode(",", $pagos);
         
    if($numero_compra=='0' && $idDetalles!='0'){
        $sqlObtener = "SELECT `id_detalle_cuentas_por_cobrar`, `valor`, `fecha_pago`, `id_cuenta_por_cobrar`, `id_forma_pago`, `banco_referencia`, `documento_numero`, `efectivo`, `saldo`, `id_plan_cuenta`, `numero_asiento` FROM `detalle_cuentas_por_cobrar` WHERE id_detalle_cuentas_por_cobrar in ('".$idDetalles."')";
        $resultObtener = mysql_query($sqlObtener);
        while($rowOb = mysql_fetch_array($resultObtener) ){
            $numero_compra = $rowOb['id_cuenta_por_cobrar'];
        }
    }
         
    function ceros($valor){
               $s='';
        for($i=1;$i<=9-strlen($valor);$i++)
                $s.="0";
        return $s.$valor;
    }
$unico=$_GET['unico'];
$unico=trim($unico)==''?0:$unico;
    $cuentaTipo=$_GET['switch-four'];


    $sql="SELECT cuentas_por_cobrar.id_venta, COUNT(detalle_cuentas_por_cobrar.id_detalle_cuentas_por_cobrar) as filas 
    FROM `cuentas_por_cobrar` 
    INNER JOIN detalle_cuentas_por_cobrar ON detalle_cuentas_por_cobrar.id_cuenta_por_cobrar= cuentas_por_cobrar.id_cuenta_por_cobrar 
    INNER JOIN (SELECT cuentas_por_cobrar.id_venta FROM cuentas_por_cobrar 
    WHERE id_cuenta_por_cobrar in('".$numero_compra."')) as selecion ON selecion.id_venta=cuentas_por_cobrar.id_venta WHERE cuentas_por_cobrar.`id_empresa` ='".$sesion."';";
//   if($sesion_id_empresa==41){
//       echo $sql;
//   }
    // echo $sql;
    
    $resultDetallesVentaC=mysql_query( $sql);
    $ODetallesVentaC=mysql_fetch_array($resultDetallesVentaC);
    // 
    $numDetalles=$ODetallesVentaC['filas'];
     $idVenta=$ODetallesVentaC['id_venta'];
    // exit;

    if($numDetalles==0 && trim($idVenta)==''){
        $sqlCtas= "SELECT cuentas_por_cobrar.id_venta, COUNT( detalle_cuentas_por_cobrar.id_detalle_cuentas_por_cobrar ) AS filas FROM `cuentas_por_cobrar` 
        INNER JOIN detalle_cuentas_por_cobrar ON detalle_cuentas_por_cobrar.id_cuenta_por_cobrar = cuentas_por_cobrar.id_cuenta_por_cobrar
        WHERE cuentas_por_cobrar.`id_empresa` = '".$sesion."' AND detalle_cuentas_por_cobrar.id_cuenta_por_cobrar IN('".$numero_compra."');";
        $resultCtas = mysql_query( $sqlCtas);
         $ODetallesCtas=mysql_fetch_array($resultCtas);
         $numDetalles=$ODetallesCtas['filas'];
    }
    
    $altura = $numDetalles*10; 
    
    $pdf = new PDF_MC_Table('P','mm',array(80,190+$altura));
    $pdf->SetMargins(4,10,4);
    $pdf->AddPage();

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
    $pdf->setY(25);$pdf->setX(5);
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

  

    $resultVenta=mysql_query( "select *,establecimientos.codigo as est,emision.codigo as emi from leads,usuarios,establecimientos,emision 
    where leads.id_usuario=usuarios.id_usuario AND  leads.id='".$id."' and establecimientos.id=usuarios.id_est and emision.id=usuarios.id_punto");
	$Oventa=mysql_fetch_array($resultVenta);
    $pdf->MultiCell(0,5,utf8_decode("Dirección Est: " .$Oventa['direccion']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Fecha Emisión: ".date("d/m/Y")),0,'C',false);
    // $pdf->MultiCell(0,5,utf8_decode("Caja Nro: 1"),0,'C',false);
    // $pdf->MultiCell(0,5,utf8_decode("Cajero: Carlos Alfaro"),0,'C',false);
    $pdf->SetFont('Arial','B',10);
    $pdf->MultiCell(0,5,utf8_decode("RECIBO DE ABONO"),0,'C',false);
    $pdf->SetFont('Arial','',9);

    $pdf->Ln(1);
    $pdf->Cell(0,5,utf8_decode("------------------------------------------------------"),0,0,'C');
    $pdf->Ln(5);

    if ($cuentaTipo=='2'){
        $sqlDatos="select nombre as name,apellido as apellido, cedula as identificacion, telefono as telefono, direccion as direccion, email as email
          FROM `clientes` clientes 
       where  clientes.id_cliente='".$id."'  ";
    }else if ($cuentaTipo=='1'){
        $sqlDatos="select nombre_comercial as name, ruc as identificacion, telefono as telefono, direccion as direccion, email as email
         FROM `proveedores` proveedores 
        where  proveedores.id_proveedor='".$id."'  ";
    }else if ($cuentaTipo=='3'){
        $sqlDatos="select name, apellido,identificacion,telefono,direccion,email from leads 
         where leads.id='".$id."'  ";
    }else if ($cuentaTipo=='4'){
        $sqlDatos="select nombre as name, apellido as apellido, cedula as identificacion, telefono as telefono,  direccion as direccion, email as email
        FROM  `empleados` empleados
        where  empleados.id_empleado='".$id."'  ";
    }

    $resultCliente=mysql_query($sqlDatos);
	$Ocliente=mysql_fetch_array($resultCliente);
    

    $pdf->MultiCell(0,5,utf8_decode("Cliente: ".$Ocliente['name']." ".$Ocliente['apellido']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("RUC/CI: ".$Ocliente['identificacion']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Teléfono: ".$Ocliente['telefono']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Dirección: ".$Ocliente['direccion']),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Email: ".$Ocliente['email']),0,'C',false);
    $pdf->Ln(1);

    /*----------  Detalles de la tabla  ----------*/
   
      if ($cuentaTipo=='2'){
       $sqlDetalle= " SELECT detalle_cuentas_por_cobrar.saldo as saldo_detalle,detalle_cuentas_por_cobrar.valor as cantidadCancelada,  cuentas_por_cobrar.* , clientes.nombre as name, clientes.cedula as identificacion, empresa.imagen , emision.codigo as emision_codigo,
    establecimientos.codigo as establecimientos_codigo ,
    ventas.numero_factura_venta as numero_factura_venta
      FROM `clientes` clientes 
    INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON clientes.`id_cliente` = cuentas_por_cobrar.`id_cliente`
       INNER JOIN empresa ON empresa.id_empresa= cuentas_por_cobrar.id_empresa
       INNER JOIN detalle_cuentas_por_cobrar ON detalle_cuentas_por_cobrar.id_cuenta_por_cobrar = cuentas_por_cobrar.id_cuenta_por_cobrar
       LEFT JOIN ventas ON ventas.id_venta = cuentas_por_cobrar.id_venta
       LEFT JOIN emision ON ventas.codigo_lug = emision.id
        LEFT JOIN establecimientos ON establecimientos.id= ventas.codigo_pun
       where  cuentas_por_cobrar.id_empresa='".$sesion."' and cuentas_por_cobrar.id_cliente='".$id."' ";
       
    }else if ($cuentaTipo=='1'){
         $sqlDetalle= " SELECT detalle_cuentas_por_cobrar.saldo as saldo_detalle,detalle_cuentas_por_cobrar.valor as cantidadCancelada, cuentas_por_cobrar.* , proveedores.nombre_comercial as name, proveedores.ruc as identificacion, empresa.imagen 
       FROM `proveedores` proveedores 
    INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON proveedores.`id_proveedor` = cuentas_por_cobrar.`id_proveedor`
       INNER JOIN empresa ON empresa.id_empresa= cuentas_por_cobrar.id_empresa
       INNER JOIN detalle_cuentas_por_cobrar ON detalle_cuentas_por_cobrar.id_cuenta_por_cobrar = cuentas_por_cobrar.id_cuenta_por_cobrar
       
       where  cuentas_por_cobrar.id_empresa='".$sesion."' and cuentas_por_cobrar.id_proveedor='".$id."' ";
       
    }else if ($cuentaTipo=='3'){
        $sqlDetalle= " SELECT detalle_cuentas_por_cobrar.saldo as saldo_detalle,detalle_cuentas_por_cobrar.valor as cantidadCancelada, cuentas_por_cobrar.* ,  name, identificacion, empresa.imagen 
       from cuentas_por_cobrar INNER JOIN leads ON leads.id = cuentas_por_cobrar.id_lead
       INNER JOIN empresa ON empresa.id_empresa= cuentas_por_cobrar.id_empresa
       INNER JOIN detalle_cuentas_por_cobrar ON detalle_cuentas_por_cobrar.id_cuenta_por_cobrar = cuentas_por_cobrar.id_cuenta_por_cobrar
       LEFT JOIN ventas ON ventas.id_venta = cuentas_por_cobrar.id_venta
       LEFT JOIN emision ON ventas.codigo_lug = emision.id
        LEFT JOIN establecimientos ON establecimientos.id= ventas.codigo_pun
       where  cuentas_por_cobrar.id_empresa='".$sesion."' and cuentas_por_cobrar.id_lead='".$id."' ";
       
    }else if ($cuentaTipo=='4'){
         $sqlDetalle= " SELECT detalle_cuentas_por_cobrar.saldo as saldo_detalle, detalle_cuentas_por_cobrar.valor as cantidadCancelada, cuentas_por_cobrar.* ,empleados.nombre as name, empleados.cedula as identificacion, empresa.imagen 
       FROM  `empleados` empleados
    INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON empleados.`id_empleado` = cuentas_por_cobrar.`id_empleado` 
       INNER JOIN empresa ON empresa.id_empresa= cuentas_por_cobrar.id_empresa
       INNER JOIN detalle_cuentas_por_cobrar ON detalle_cuentas_por_cobrar.id_cuenta_por_cobrar = cuentas_por_cobrar.id_cuenta_por_cobrar
       LEFT JOIN ventas ON ventas.id_venta = cuentas_por_cobrar.id_venta
       LEFT JOIN emision ON ventas.codigo_lug = emision.id
        LEFT JOIN establecimientos ON establecimientos.id= ventas.codigo_pun
       where  cuentas_por_cobrar.id_empresa='".$sesion."' and cuentas_por_cobrar.id_empleado='".$id."' ";
    }

if(trim($idVenta)!='' && $numero_compra=='0'){
    $sqlDetalle.= " and cuentas_por_cobrar.id_venta='".$idVenta."'  "; 
}else{
    $sqlDetalle.= " and cuentas_por_cobrar.id_cuenta_por_cobrar in ('".$numero_compra."')  "; 
}
  
   
    if($numero_compra!='0' && $unico==1){
        $sqlDetalle.= " and detalle_cuentas_por_cobrar.id_detalle_cuentas_por_cobrar in('".$idDetalles."' )  " ;
    }
//  if($sesion==41){
//     echo $sqlDetalle;
//     exit;
//  }
// echo $sqlDetalle;
     $resultDetalleVenta=mysql_query( $sqlDetalle);
    $data2=array();
    $data2[0][]='FECHA';
    $data2[0][]='CONCEPTO';
    $data2[0][]='# FACTURA';
    $data2[0][]='VALOR';
    $data2[0][]='SALDO';
      
    
$suma=0;

$z= 1;

$pdf->SetWidths(array(13,14,22,10,10));
$pdf->SetAligns(array('C','C','C','R','R'));
$pdf->SetLineHeight(5);
$totalF=0;
$numeroAbono=0;
$idAnterior='';
$costo=0;
 $pdf->SetFont('Arial','B',6);
    while($ODetalleVenta=mysql_fetch_array($resultDetalleVenta)){
        
        if($ODetalleVenta['id_cuenta_por_cobrar']!=$idAnterior){
            $numeroAbono++;
            $idAnterior=$ODetalleVenta['id_cuenta_por_cobrar'];
            $costo=$ODetalleVenta['valor'];
        }
    $timestamp = strtotime($ODetalleVenta["fecha_pago"]);
    $fecha_sin_hora = date('Y-m-d', $timestamp);

        $data2[$z][]=$fecha_sin_hora;
         $data2[$z][]='ABONO # '.$numeroAbono;
        if($cuentaTipo=='2'){
             $data2[$z][]=$ODetalleVenta['establecimientos_codigo']."-".$ODetalleVenta['emision_codigo']."-".ceros($ODetalleVenta['numero_factura_venta']);
        }else{
             $data2[$z][]=ceros($ODetalleVenta['numero_factura']);
        }
   
        $data2[$z][]='$ '.number_format($ODetalleVenta["cantidadCancelada"], 2, '.', ' ');
        $data2[$z][]='$ '.number_format($ODetalleVenta["saldo_detalle"], 2, '.', ' ');
        $totalF = $totalF+$ODetalleVenta["cantidadCancelada"];
        $z++;
        $costo=$costo-$ODetalleVenta["cantidadCancelada"];
    }

    $d=0;
    $contar=0;
    foreach($data2 as $item){
        if($contar==0){
              $posTitulo= $pdf->GetY();
    $pdf->Line(5,$posTitulo,76,$posTitulo);
        }
$pdf->SetFont('Arial','B',6);
      $pdf->Row(array(
        $item[0],
        $item[1],
        $item[2],
        $item[3],
        $item[4]
      ));
    if($contar==0){
         $posTitulo= $pdf->GetY();
    $pdf->Line(5,$posTitulo,76,$posTitulo);
      
        $pdf->Ln(1);
        }
        $contar++;
      $d++;
    }
    /*----------  Fin Detalles de la tabla  ----------*/
$pdf->SetFont('Arial','B',7);


     $posTitulo= $pdf->GetY();
    $pdf->Line(5,$posTitulo,76,$posTitulo);

    $pdf->Ln(3);
    

    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(22,5,utf8_decode("Total cancelado"),0,0,'R');
    $pdf->Cell(20,5,'$ '.number_format($totalF, 2, '.', ' '),0,0,'R');

    $pdf->Ln(5);

    // if($Oempresa['leyenda3']!=''){
    //     $pdf->MultiCell(0,5,utf8_decode($Oempresa['leyenda3']),0,'C',false);
    // }
    
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(0,7,utf8_decode("Gracias por su compra"),'',0,'C');
    
    $posY= $pdf->GetY()+20;
    $pdf->Line(5,$posY,35,$posY);
    $pdf->Line(42,$posY,72,$posY);
    $pdf->SetFont('Arial','B',7);
    
    $pdf->SetY($posY+2);
    $pdf->SetX(5);
    $pdf->Cell(30,5,'FIRMA ENTREGADO',0,0,'R');
    $pdf->Cell(7,5,'',0,0,'R');
    $pdf->Cell(30,5,'FIRMA RECIBIDO',0,0,'R');
    
    
// if($Oventa['ClaveAcceso']!=''){
//     $pdf->Ln(9);
//      # Codigo de barras #
//     $pdf->Code128(5,$pdf->GetY(),$Oventa['ClaveAcceso'],70,10);
//     $pdf->SetXY(0,$pdf->GetY()+10);
//     $pdf->SetFont('Arial','',7);
//     $pdf->MultiCell(0,5,utf8_decode($Oventa['ClaveAcceso']),0,'C',false);
// }
   
    
    # Nombre del archivo PDF #
    $pdf->Output("I","Abono",true);