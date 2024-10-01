<?php
   session_start();
	# Incluyendo librerias necesarias #
	
	require_once('../conexion.php');
    // require "./code128.php";
        include('pdf_mc_table.php');
    
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
  $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
  
//   $sesion_id_empresa=1660;
//   $sesion_id_periodo_contable = 1645;
  
      $estado= $_GET['estado'];
    $criterio =$_GET['criterio_ordenar_por'];
    $orden = $_GET['criterio_orden'];	
    $numeroRegistros= $_GET['criterio_mostrar'];
  
    function ceros($valor){
               $s='';
        for($i=1;$i<=9-strlen($valor);$i++)
                $s.="0";
        return $s.$valor;
    }
    
    
    $fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
    $fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
    $txtProveedor =  $_GET['txtProveedor'];
    
    $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
    $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");


    // $pdf = new PDF_Code128('L','mm','A4');
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
    $pdf->MultiCell(0,5,'Reporte de conciliaciones bancarias',0,'C',false);
    
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
    $banco  =isset( $_GET['cmbNombreCuentaCB'])?$_GET['cmbNombreCuentaCB'] : 0;
    $fecha_desde = $_GET['txtFechaDesde'];
    $fecha_hasta = $_GET['txtFechaHasta'];
    
    $sql = " SELECT
                 detalle_bancos.`id_detalle_banco` AS detalle_bancos_id_detalle_banco,
                 detalle_bancos.`tipo_documento` AS detalle_bancos_tipo_documento,
                 detalle_bancos.`numero_documento` AS detalle_bancos_numero_documento,
                 detalle_bancos.`detalle` AS detalle_bancos_detalle,
                 detalle_bancos.`valor` AS detalle_bancos_valor,
                 detalle_bancos.`fecha_cobro` AS detalle_bancos_fecha_cobro,
                 detalle_bancos.`fecha_vencimiento` AS detalle_bancos_fecha_vencimiento,
                 detalle_bancos.`id_bancos` AS detalle_bancos_id_bancos,
                 detalle_bancos.`estado` AS detalle_bancos_estado,
                 detalle_bancos.`id_libro_diario` AS detalle_bancos_id_libro_diario,
                 bancos.`id_bancos` AS bancos_id_bancos,
                 bancos.`id_plan_cuenta` AS bancos_id_plan_cuenta,
                 bancos.`saldo_conciliado` AS bancos_saldo_conciliado,
                 bancos.`id_periodo_contable` AS bancos_id_periodo_contable,
                   plan_cuentas.nombre as nombre_Banco
            FROM
                 `bancos` bancos 
        INNER JOIN `detalle_bancos` detalle_bancos ON bancos.`id_bancos` = detalle_bancos.`id_bancos`
        INNER JOIN plan_cuentas ON
 plan_cuentas.id_plan_cuenta = bancos.id_plan_cuenta
        WHERE  bancos.`id_periodo_contable`='".$sesion_id_periodo_contable."'
   ";
	if ($banco!='0'){
		$sql.= " and  bancos.`id_bancos` ='".$banco."' "; 
	} 
	
	if ($fecha_desde !='' && $fecha_hasta !='' ){
		$sql.= " and DATE_FORMAT( detalle_bancos.`fecha_cobro`,'%Y-%m-%d')>=DATE_FORMAT('".$fecha_desde."','%Y-%m-%d') AND DATE_FORMAT( detalle_bancos.`fecha_cobro`,'%Y-%m-%d')<=DATE_FORMAT('".$fecha_hasta."','%Y-%m-%d') "; 
	}
	$sql .= " GROUP BY bancos.`id_bancos` ";
    $result = mysql_query($sql) or die(mysql_error());
    $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
    $numero = 0;
    $currentCodImp = null;
 
    while($row = mysql_fetch_array($result)) {
	    
	    $BasImp= $row['BaseImp'];
	    $CodImp= $row['bancos_id_bancos'];
	    $CodPorcentaje= $row['Porcentaje'];
	    
	    if ($currentCodImp != $CodImp) {
	            
	        $pdf->Cell(75,10,"Banco :".$row['nombre_Banco'],$borde,0,'L',$relleno);
            $pdf->Ln();
            
	        $sql2 = " SELECT
                 detalle_bancos.`id_detalle_banco` AS detalle_bancos_id_detalle_banco,
                 detalle_bancos.`tipo_documento` AS detalle_bancos_tipo_documento,
                 detalle_bancos.`numero_documento` AS detalle_bancos_numero_documento,
                 detalle_bancos.`detalle` AS detalle_bancos_detalle,
                 detalle_bancos.`valor` AS detalle_bancos_valor,
                 detalle_bancos.`fecha_cobro` AS detalle_bancos_fecha_cobro,
                 detalle_bancos.`fecha_vencimiento` AS detalle_bancos_fecha_vencimiento,
                 detalle_bancos.`id_bancos` AS detalle_bancos_id_bancos,
                 detalle_bancos.`estado` AS detalle_bancos_estado,
                 detalle_bancos.`id_libro_diario` AS detalle_bancos_id_libro_diario,
                 bancos.`id_bancos` AS bancos_id_bancos,
                 bancos.`id_plan_cuenta` AS bancos_id_plan_cuenta,
                 bancos.`saldo_conciliado` AS bancos_saldo_conciliado,
                 bancos.`id_periodo_contable` AS bancos_id_periodo_contable,
                   plan_cuentas.nombre as nombre_Banco
            FROM
                 `bancos` bancos 
        INNER JOIN `detalle_bancos` detalle_bancos ON bancos.`id_bancos` = detalle_bancos.`id_bancos`
        INNER JOIN plan_cuentas ON
 plan_cuentas.id_plan_cuenta = bancos.id_plan_cuenta
        WHERE  bancos.`id_periodo_contable`='".$sesion_id_periodo_contable."' and  bancos.`id_bancos` ='".$row['bancos_id_bancos']."' "; 
	
	if ($fecha_desde !='' && $fecha_hasta !='' ){
		$sql2.= " and DATE_FORMAT( detalle_bancos.`fecha_cobro`,'%Y-%m-%d')>=DATE_FORMAT('".$fecha_desde."','%Y-%m-%d') AND DATE_FORMAT( detalle_bancos.`fecha_cobro`,'%Y-%m-%d')<=DATE_FORMAT('".$fecha_hasta."','%Y-%m-%d') "; 
	}
	if ($estado!='0'){
	    $estado = ($estado==1)?'Conciliado':'No Conciliado';
		$sql2.= " and  detalle_bancos.`estado` ='".$estado."' "; 
	}  
 
    // $sql2.= " ORDER BY $criterio $orden " ;
     $sql2.= " ORDER BY detalle_bancos.`fecha_cobro` asc  " ;
     
                    $result2 = mysql_query($sql2) or die(mysql_error());     
                        
                    $pdf->Cell(25,10,'# Fecha',$borde2,0,'L',$relleno2);
                    // $pdf->Cell(20,10,'Cpte',$borde2,0,'L',$relleno2);
                    $pdf->Cell(135,10,'Descripcion',$borde2,0,'L',$relleno2);
                    $pdf->Cell(35,10,'Documento',$borde2,0,'L',$relleno2);
                     $pdf->Cell(20,10,' Nro.',$borde2,0,'L',$relleno2);
                    $pdf->Cell(25,10,'Debitos',$borde2,0,'R',$relleno2);
                     $pdf->Cell(25,10,'Creditos',$borde2,0,'R',$relleno2);
                     $pdf->Cell(25,10,'Saldo',$borde2,0,'R',$relleno2);
                    $pdf->Ln();
                    
       
                   $sumaDebitos='0';
                    $sumaCreditos='0';
                    $data2=array();
                    $z=0;
                    $pdf->SetWidths(array(25, 135,35,20,25, 25, 25));
$pdf->SetAligns(array('L', 'L', 'L', 'L','R','R','R'));
$pdf->SetLineHeight(5);
 $saldo =0;
            	    while($row2 = mysql_fetch_array($result2)) {
            	        $debito_actual = 0;
            	        $credito_actual = 0;
            	       
            	   if($row2['detalle_bancos_tipo_documento']=='Deposito'){
            	        $credito_actual = $row2['detalle_bancos_valor'];
            	        $saldo += $credito_actual ;
            	   }
            	    if($row2['detalle_bancos_tipo_documento']=='Transferencia'){
            	        $credito_actual = $row2['detalle_bancos_valor'];
            	        $saldo += $credito_actual ;
            	   }
            	    if($row2['detalle_bancos_tipo_documento']=='Nota de Credito'){
            	        $credito_actual = $row2['detalle_bancos_valor'];
            	        $saldo += $credito_actual ;
            	   }
            	   
            	     if($row2['detalle_bancos_tipo_documento']=='Transferenciac'){
            	        $debito_actual = $row2['detalle_bancos_valor'];
            	        $saldo -= $debito_actual ;
            	   }
            	    if($row2['detalle_bancos_tipo_documento']=='Cheque'){
            	        $debito_actual =$row2['detalle_bancos_valor'];
            	        $saldo -= $debito_actual ;
            	    }
            	    if($row2['detalle_bancos_tipo_documento']=='Nota de Debito'){
            	        $debito_actual =$row2['detalle_bancos_valor'];
            	        $saldo -= $debito_actual ;
            	   }
            	  
            	   
            	    $data2[$z][]=$row2["detalle_bancos_fecha_cobro"];
                    //   $data2[$z][]=$row2["detalle_bancos_numero_documento"];
                        $data2[$z][]=utf8_decode($row2['detalle_bancos_detalle']);
                        $data2[$z][]= $row2['detalle_bancos_tipo_documento'];
                         $data2[$z][]= ' #'.$row2["detalle_bancos_numero_documento"];
                        $data2[$z][]=number_format($debito_actual, 3);
                        $data2[$z][]=number_format($credito_actual, 3);
                         $data2[$z][]=number_format($saldo, 3);
 
                    // $pdf->Cell(40,10,$row2['detalle_bancos_fecha_cobro'],$borde3,0,'L',$relleno3);
                    //  $pdf->Cell(20,10,$row2['detalle_bancos_numero_documento'],$borde3,0,'L',$relleno3);
                    // $pdf->Cell(140,10,utf8_decode($row2['detalle_bancos_detalle']),$borde3,0,'L',$relleno3);
                    // $pdf->Cell(40,10,$row2['detalle_bancos_numero_documento'],$borde3,0,'L',$relleno3);
                    // $pdf->Cell(25,10,$debito_actual ,$borde3,0,'R',$relleno3);
                    // $pdf->Cell(25,10,$credito_actual,$borde3,0,'R',$relleno3);
                    // $pdf->Ln();
                 
                    $sumaDebitos= $sumaDebitos +$debito_actual;
                     $sumaCreditos= $sumaCreditos +$credito_actual;
                     $z++;
            	}   
            
            	foreach($data2 as $item)
                {

                $pdf->Row(array(
                    $item[0],
                    $item[1],
                    $item[2],
                    $item[3],
                    $item[4],
                    $item[5],
                    $item[6],
                  ));
                }

            	
            	    $pdf->Cell(215,10,'','',0,'R','');
                    $pdf->Cell(25,10,number_format($sumaDebitos, 3),$borde,0,'R',$relleno);
                    $pdf->Cell(25,10,number_format($sumaCreditos, 3),$borde,0,'R',$relleno);
                    $pdf->Cell(25,10,number_format($saldo, 3),$borde,0,'R',$relleno);
            	    $pdf->Ln();
                    $pdf->Ln(4);
            	
	    }
	    
	    
	    $currentCodImp = $CodImp;
    }
    

    # Nombre del archivo PDF #
    $pdf->Output("I","Retenciones.pdf",true);