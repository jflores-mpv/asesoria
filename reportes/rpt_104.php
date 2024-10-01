<?php
   session_start();
	# Incluyendo librerias necesarias #
	
	require_once('../conexion.php');
    // require "./code128.php";
     require "./pdf_mc_table.php";
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];

    $fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
    $fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
    $txtProveedor =  $_GET['txtProveedor'];
    
    $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
    $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
 function covertirFecha($fecha){
        $cadena=split("-",$fecha);// elimina el /
       
        $mes1 = $cadena[1];// guarda en variable
        $mes=floatval($mes1);// elima el cero
    
        $mesletra = "";
        switch($mes)
        {
            case 1:$mesletra="Enero";break;
            case 2:$mesletra="Febrero";break;
            case 3:$mesletra="Marzo";break;
            case 4:$mesletra="Abril";break;
            case 5:$mesletra="Mayo";break;
            case 6:$mesletra="Junio";break;
            case 7:$mesletra="Julio";break;
            case 8:$mesletra="Agosto";break;
            case 9:$mesletra="Septiembre";break;
            case 10:$mesletra="Octubre";break;
            case 11:$mesletra="Noviembre";break;
            case 12:$mesletra="Diciembre";break;
        }
      
        $fechanueva = $mesletra;
        return $fechanueva;
    }

    // $pdf = new PDF_Code128('P','mm','A4');
    $pdf = new PDF_MC_Table('P','mm','A4');
    $pdf->SetMargins(4,10,4);
    $pdf->AddPage();
    
    
    $resultEmpresa=mysql_query( "select * from empresa where id_empresa='".$sesion_id_empresa."' ");
	$Oempresa=mysql_fetch_array($resultEmpresa);
    
    # Encabezado y datos de la empresa #
    $pdf->Ln(4);
    $pdf->SetFont('Arial','B',15);
    $pdf->SetTextColor(0,0,0);
    $pdf->MultiCell(0,5,'SERVICIO DE RENTAS INTERNAS',0,'C',false);
    $hoy= date('Y-m-d');
    $hoy2= date('Y-m');
     $hoy3= date('m/Y');
    
     $pdf->Ln(4);
    $pdf->SetX(10);
    $pdf->SetFont('Arial','',11);
    $pdf->SetX(10);
     $pdf->Cell(40,10,'RUC: ',$borde2,0,'R',$relleno2);
     $pdf->SetFont('Arial','B',11);
     $pdf->Cell(40,10,$Oempresa['ruc'],$borde2,1,'L',$relleno2);
     $pdf->SetX(10);
     $pdf->SetFont('Arial','',11);
     $pdf->Cell(40,10,utf8_decode("RAZÓN SOCIAL: "),$borde2,0,'R',$relleno2);
     $pdf->SetFont('Arial','B',11);
     $pdf->Cell(40,10,$Oempresa['razonSocial'],$borde2,1,'L',$relleno2);
     $pdf->SetX(10);
     $pdf->SetFont('Arial','',11);
     $pdf->Cell(40,10,'IMPUESTO: ',$borde2,0,'R',$relleno2);
     $pdf->SetFont('Arial','B',11);
     $pdf->Cell(40,10,'Retenciones en la Fuente del Impuesto a la Renta',$borde2,1,'L',$relleno2);
     $pdf->SetX(10);
     $pdf->SetFont('Arial','',11);
     $pdf->Cell(40,10,'PERIODO FISCAL: ',$borde2,0,'R',$relleno2);
     $pdf->SetFont('Arial','B',11);
     $pdf->Cell(40,10,$hoy3,$borde2,1,'L',$relleno2);

        $pdf->Ln(4);
        
        
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
    
    $sql = "SELECT d.*

    FROM dcretencion d 
    JOIN mcretencion m ON d.Retencion_id = m.Id 
    JOIN compras c ON m.Factura_id = c.id_compra 
        
    where c.id_empresa=$sesion_id_empresa and  c.fecha_compra >= '".$fecha_desde."' AND c.fecha_compra <= '".$fecha_hasta."'
        
    ORDER BY d.CodImp ";

    $result = mysql_query($sql) or die(mysql_error());
    $detalleY = $pdf->GetY();
    $detalleX = $pdf->GetX();
    $pdf->Line(10,$detalleY, 200, $detalleY);
    $pdf->SetX(10);
        $pdf->Cell(25,10,'CAMPO',$borde3,0,'C',1);
        $pdf->Cell(120,10,utf8_decode('DESCRIPCIÓN'),$borde3,0,'C',1);
        $pdf->Cell(45,10,'VALOR',$borde3,0,'C',1);
        $pdf->Ln();
    $pdf->Line(10,$detalleY+10, 200, $detalleY+10);
    
    $mes='Enero';
    // $total=950;
    // $pdf->SetX(10);
    // $pdf->Cell(45,10,'031',$borde3,0,'L',$relleno3);
    // $pdf->Cell(100,10,' ORIGINAL - SUSTITUTIVA _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _',$borde3,0,'R',$relleno3);
    // $pdf->Cell(45,10,$total,$borde3,0,'R',$relleno3);
    //                 $pdf->Ln();
                    
    // $pdf->SetX(10);
    // $pdf->Cell(45,10,'999',$borde3,0,'L',$relleno3);
    // $pdf->Cell(100,10,' TOTAL PAGADO _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _',$borde3,0,'R',$relleno3);
    // $pdf->Cell(45,10,$total,$borde3,0,'R',$relleno3);
    //                 $pdf->Ln();
      $data2=array();

  
  $data2[0][]='102';
    $data2[0][]=utf8_decode('AÑO');
    $data2[0][]=date('Y');
    
  
    $data2[1][]='101';
    $data2[1][]='MES';
    $data2[1][]=covertirFecha($hoy);
  
    $data2[2][]='31';
    $data2[2][]='ORIGINAL - SUSTITUTIVA';
    $data2[2][]='ORIGINAL';
    
     $data2[3][]='202';
    $data2[3][]=utf8_decode('RAZÓN SOCIAL');
    $data2[3][]=$Oempresa['razonSocial'];
    
    //  $data2[4][]='199';
    // $data2[4][]='RUC CONTADOR';
    // $data2[4][]=$Oempresa['ruc_contador'];
    
    
    // $data2[5][]='198';
    // $data2[5][]='No. ID REPRESENTANTE LEGAL';
    // $data2[5][]=$Oempresa['ruc_representante'];
    
   
    
    $data2[4][]='201';
    $data2[4][]='RUC';
    $data2[4][]=$Oempresa['ruc'];
    
    $data2[5][]='413';
    $data2[5][]='Valor neto - Ventas locales (excluye activos fijos) gravadas tarifa 0% que no dan drecho a crédito';
    $data2[5][]='3,118.00';
    
    $data2[6][]='403';
    $data2[6][]=' Valor bruto - Ventas locales (excluye activos fijos) gravadas tarifa 0% que no dan drecho a crédito';
    $data2[6][]='3,118.00';
    
    $data2[7][]='419';
    $data2[7][]='TOTAL VENTAS Y OTRAS OPERACIONES - VALOR NETO';
    $data2[7][]='3,118.00';
    
    $data2[8][]='409';
    $data2[8][]='TOTAL VENTAS Y OTRAS OPERACIONES - VALOR BRUTO';
    $data2[8][]='3,118.00';
    
    $data2[9][]='510';
    $data2[9][]='Valor neto - Adquisiciones y pagos (excluye activos fijos) gravados tarifa diferente de cero (con';
    $data2[9][]='10,478.92';
    
    $data2[10][]='500';
    $data2[10][]='Valor bruto - Adquisiciones y pagos (excluye activos fijos) gravados tarifa diferente de cero (con';
    $data2[10][]='10,478.92';
    
    $data2[11][]='520';
    $data2[11][]='Impuesto generado - Adquisiciones y pagos (excluye activos fijos) gravados tarifa diferente de cero';
    $data2[11][]='1,257.47';
    
    $data2[12][]='512';
    $data2[12][]='Valor neto - Otras adquisiciones y pagos gravados tarifa diferente de cero (sin derecho a crédito';
    $data2[12][]='1,298.57';
    
    $data2[13][]='502';
    $data2[13][]='Valor bruto - Otras adquisiciones y pagos gravados tarifa diferente de cero (sin derecho a crédito';
    $data2[13][]='1,298.57';
    
    $data2[14][]='522';
    $data2[14][]='Impuesto generado - Otras adquisiciones y pagos gravados tarifa diferente de cero (sin derecho a';
    $data2[14][]='155.83';
    
    $data2[15][]='517';
    $data2[15][]='Valor neto - Adquisiciones y pagos (incluye activos fijos) gravados tarifa 0%';
    $data2[15][]='2,325.00';
    
    $data2[16][]='507';
    $data2[16][]=' Valor bruto - Adquisiciones y pagos (incluye activos fijos) gravados tarifa 0%';
    $data2[16][]='2,325.00';
    
    $data2[17][]='529';
    $data2[17][]='TOTAL ADQUISICIONES Y PAGOS - IMPUESTO GENERADO';
    $data2[17][]='1,413.30';
    
    $data2[18][]='519';
    $data2[18][]='TOTAL ADQUISICIONES Y PAGOS - VALOR NETO';
    $data2[18][]='14,102.49';
    
    $data2[19][]='509';
    $data2[19][]=' TOTAL ADQUISICIONES Y PAGOS - VALOR BRUTO';
    $data2[19][]='14,102.49';
    
    $data2[20][]='541';
    $data2[20][]='Valor neto - Adquisiciones no objeto de IVA';
    $data2[20][]='4,500.00';
    
    $data2[21][]='531';
    $data2[21][]='Valor bruto - Adquisiciones no objeto de IVA';
    $data2[21][]='4,500.00';
    
    $data2[22][]='731';
    $data2[22][]='Retención del 100%';
    $data2[22][]='1,413.30';
    
    $data2[23][]='799';
    $data2[23][]='TOTAL IMPUESTO RETENIDO';
    $data2[23][]='1,413.30';
    
    $data2[24][]='801';
    $data2[24][]='TOTAL IMPUESTO A PAGAR POR RETENCION';
    $data2[24][]='1,413.30';
    
    $data2[25][]='859';
    $data2[25][]='TOTAL CONSOLIDADO DE IMPUESTO AL VALOR AGREGADO (699+799)';
    $data2[25][]='1,413.30';
    
    $data2[26][]='902';
    $data2[26][]='TOTAL IMPUESTO A PAGAR';
    $data2[26][]='1,413.30';

	 $data2[27][]='999';
    $data2[27][]='Total Pagado';
    $data2[27][]=200;
    
    $data2[28][]='905';
    $data2[28][]='MEDIANTE CHEQUE, DÉBITO BANCARIO, EFECTIVO U OTRAS FORMAS DE PAGO';
    $data2[28][]='1,413.30';
    
    $data2[29][]='198';
    $data2[29][]='No. ID REPRESENTANTE LEGAL';
    $data2[29][]='1709785867';
    
    $data2[30][]='199';
    $data2[30][]=' RUC CONTADOR';
    $data2[30][]='2100206859001';
    
    $data2[31][]='922';
    $data2[31][]='BANCO';
    $data2[31][]=' BANCO CENTRAL DEL ECUADOR';
    
    $data2[32][]='921';
    $data2[32][]='FORMA DE PAGO';
    $data2[32][]='Convenio de debito';
    
    
    $pdf->SetFont('Arial','',7);
	 $pdf->SetWidths(array(25,120,45));
    $pdf->SetLineHeight(5);
$pdf->Ln(2);
	 foreach($data2 as $item){
	     
$pdf->SetX(12);
      $pdf->Row(array(
        $item[0],
        utf8_decode($item[1]),
        $item[2],
      ));

    }
    
    // $pdf->Cell(150,10,'Total Pagado','',0,'R','');
    // $pdf->Cell(25,10,$sumaBaseImp,$borde,0,'R',$relleno);
    $pdf->Ln();
    $posicionV = $pdf->GetY()+10;
    $pdf->Line(40,$posicionV, 90, $posicionV);
    $pdf->Line(110,$posicionV, 170, $posicionV);
    $pdf->SetY($posicionV);
    $pdf->SetX(45);
    $pdf->Cell(40,10,'Firma Representante Legal',$borde3,0,'C',$relleno3);
    $pdf->SetX(125);
    $pdf->Cell(25,10,'Firma Contador (a)',$borde3,0,'C',$relleno3);
    $pdf->SetY($posicionV+5);
    $pdf->SetX(45);
    $pdf->Cell(40,10,'C.I.',$borde3,0,'C',$relleno3);
    $pdf->SetX(125);
    $pdf->Cell(25,10,'C.I.',$borde3,0,'C',$relleno3);
      
      
    $finalDocumento = $pdf->GetY()+20;  
    $pdf->Line(10,10, 200, 10);
    $pdf->Line(10,10, 10, $finalDocumento);
    $pdf->Line(200,10, 200, $finalDocumento);
    $pdf->Line(10,$finalDocumento, 200, $finalDocumento);
    // $pdf->Line(10,10, 200, 10);
    # Nombre del archivo PDF #
    $pdf->Output("I","Servicio_Rentas_Internas.pdf",true);