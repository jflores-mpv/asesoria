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
  $fecha_actual_ym  =date('Y-m');
    function ceros($valor){
               $s='';
        for($i=1;$i<=9-strlen($valor);$i++)
                $s.="0";
        return $s.$valor;
    }
       function calculoMayorizacion($f_desde,$f_hasta, $periodo_contable,$sesion_id_empresa, $id_plan_cuenta){
        $sesion_id_periodo_contable = $periodo_contable;
        $fecha_desde_principal = explode(" ", $f_desde);
        $fecha_hasta_principal = explode(" ", $f_hasta);
        $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
        $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
        
        $sql = "SELECT
     mayorizacion.`id_mayorizacion` AS mayorizacion_id_mayorizacion,
     mayorizacion.`id_plan_cuenta` AS mayorizacion_id_plan_cuenta,
     mayorizacion.`id_periodo_contable` AS mayorizacion_id_periodo_contable,
     periodo_contable.`estado` AS periodo_contable_estado,
     plan_cuentas.`codigo` AS plan_cuentas_codigo,
     plan_cuentas.`nombre` AS plan_cuentas_nombre,
     plan_cuentas.`id_empresa` AS plan_cuentas_id_empresa,
     libro_diario.`fecha` AS libro_diario_fecha, libro_diario.`numero_asiento` AS numero_asiento
     
FROM
     `mayorizacion` mayorizacion INNER JOIN `periodo_contable` periodo_contable ON mayorizacion.`id_periodo_contable` = periodo_contable.`id_periodo_contable`
     INNER JOIN `plan_cuentas` plan_cuentas ON mayorizacion.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
     INNER JOIN `libro_diario` libro_diario ON periodo_contable.`id_periodo_contable` = libro_diario.`id_periodo_contable` ";

        if ($id_plan_cuenta >= 1){
		$sql .= " WHERE libro_diario.`fecha` BETWEEN '".$fecha_desde."' and '".$fecha_hasta."' AND plan_cuentas.`id_empresa` = '".$sesion_id_empresa."'
		AND plan_cuentas.`id_plan_cuenta` = '".$id_plan_cuenta."' AND periodo_contable.`id_periodo_contable` = '".$sesion_id_periodo_contable."' ";}
                else {
                $sql .= " WHERE libro_diario.`fecha` BETWEEN '".$fecha_desde."' and '".$fecha_hasta."' 
                AND plan_cuentas.`id_empresa` = '".$sesion_id_empresa."' AND plan_cuentas.`id_plan_cuenta` like '".$id_plan_cuenta."%' 
                AND periodo_contable.`id_periodo_contable` = '".$sesion_id_periodo_contable."' ";}
            
		$sql .= " GROUP BY plan_cuentas.`codigo` ";
// 		echo $sql;
// 		exit;
        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
        $numero = 0;
       
          while($row = mysql_fetch_array($result)){
            $numero ++;
          
            $mayorizacion_id_plan_cuenta = $row['mayorizacion_id_plan_cuenta'];
            $plan_cuentas_codigo = $row['plan_cuentas_codigo'];
            $plan_cuentas_nombre = $row['plan_cuentas_nombre'];

            $sql2 = "SELECT
     detalle_libro_diario.`id_libro_diario`,
     detalle_libro_diario.`debe`,
     detalle_libro_diario.`haber`,
     libro_diario.`id_libro_diario` AS libro_diario_id_libro_diario,
     libro_diario.`fecha` AS libro_diario_fecha,
     libro_diario.`numero_asiento` AS numero_asiento
     
FROM
     `libro_diario` libro_diario INNER JOIN `detalle_libro_diario` detalle_libro_diario 
     ON libro_diario.`id_libro_diario` = detalle_libro_diario.`id_libro_diario`
           AND libro_diario.id_periodo_contable= detalle_libro_diario.`id_periodo_contable`
            WHERE libro_diario.`fecha` BETWEEN '".$fecha_desde."' and '".$fecha_hasta."' and 
            detalle_libro_diario.`id_plan_cuenta`='".$mayorizacion_id_plan_cuenta."' AND 
            detalle_libro_diario.`id_periodo_contable`='".$sesion_id_periodo_contable."' ";

            $result2=mysql_query($sql2);
            $numFilasDetallesLB= mysql_num_rows($result2);
           
            if($numFilasDetallesLB>0){
                 $debe_detalle_mayorizacion = array();
            $haber_detalle_mayorizacion = array();
            $id_libro_diario = array();
            $id_libro_diario2 = "";
            $fecha = "";
            $numero_comprobante = "";
            $b=0;
            $sumadebe = 0;
            $sumahaber = 0;
            $numero_filas_detalle_mayorizacion = mysql_num_rows($result2); // obtenemos el número de filas
            while($row2=mysql_fetch_array($result2))//permite ir de fila en fila de la tabla
            {
                $sumadebe = $sumadebe + $row2['debe'];
                $sumahaber = $sumahaber + $row2['haber'];
                $debe_detalle_mayorizacion[$b] = $row2['debe'];
                $haber_detalle_mayorizacion[$b] = $row2['haber'];
                $id_libro_diario[$b] = $row2['id_libro_diario'];
                $b++;
            }
            return number_format($sumadebe-$sumahaber, 2, '.', ' ');

            }
           
        }
      
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
    
$sql = "SELECT *,
           detalle_bancos.id_detalle_banco AS detalle_bancos_id_detalle_banco,
           detalle_bancos.tipo_documento AS detalle_bancos_tipo_documento,
           detalle_bancos.numero_documento AS detalle_bancos_numero_documento,
           detalle_bancos.detalle AS detalle_bancos_detalle,
           detalle_bancos.valor AS detalle_bancos_valor,
           detalle_bancos.fecha_cobro AS detalle_bancos_fecha_cobro,
           detalle_bancos.fecha_vencimiento AS detalle_bancos_fecha_vencimiento,
           detalle_bancos.id_bancos AS detalle_bancos_id_bancos,
           detalle_bancos.estado AS detalle_bancos_estado,
           detalle_bancos.id_libro_diario AS detalle_bancos_id_libro_diario,
           bancos.id_bancos AS bancos_id_bancos,
           bancos.id_plan_cuenta AS bancos_id_plan_cuenta,
           bancos.saldo_conciliado AS bancos_saldo_conciliado,
           bancos.id_periodo_contable AS bancos_id_periodo_contable,
           plan_cuentas.nombre AS nombre_Banco,
           (SELECT SUM(CASE 
                         WHEN LOWER(d.tipo_documento) IN ('cheque', 'nota de debito', 'transferenciac') THEN -d.valor
                         WHEN LOWER(d.tipo_documento) IN ('deposito', 'nota de credito', 'transferencia') THEN d.valor
                         ELSE 0
                       END)
            FROM detalle_bancos d
            WHERE d.id_bancos = bancos.id_bancos AND d.estado = 'Conciliado'
            ORDER BY d.id_detalle_banco ASC) AS saldoTotal3
        FROM bancos
        INNER JOIN detalle_bancos ON bancos.id_bancos = detalle_bancos.id_bancos
        INNER JOIN plan_cuentas ON plan_cuentas.id_plan_cuenta = bancos.id_plan_cuenta
        WHERE bancos.id_periodo_contable = '".$sesion_id_periodo_contable."'";

if ($banco != '0') {
    $sql .= " AND bancos.id_bancos = '".$banco."'";
}

if ($fecha_desde != '' && $fecha_hasta != '') {
    $sql .= " AND DATE_FORMAT(detalle_bancos.fecha_cobro, '%Y-%m-%d') BETWEEN DATE_FORMAT('".$fecha_desde."', '%Y-%m-%d') AND DATE_FORMAT('".$fecha_hasta."', '%Y-%m-%d')";
}

$sql .= " GROUP BY bancos.id_bancos";


    $result = mysql_query($sql) or die(mysql_error());
    $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
    $numero = 0;
    $currentCodImp = null;
 
    while($row = mysql_fetch_array($result)) {

	    
	    $BasImp= $row['BaseImp'];
	    $CodImp= $row['bancos_id_bancos'];
	    $CodPorcentaje= $row['Porcentaje'];
	    
	    if ($currentCodImp != $CodImp) {
	        $bancos_id_plan_cuenta = $row['bancos_id_plan_cuenta'];
	        
	         $sqlBco="SELECT `id_info`, `fecha_desde`, `fecha_hasta`, `valor_registrado`, `valor_sistema`, `cuenta_banco`, `id_banco`, `id_empresa` FROM `info_detalle_bancos` WHERE id_empresa=$sesion_id_empresa AND id_banco=$CodImp AND  DATE_FORMAT(fecha_desde, '%Y-%m') =  DATE_FORMAT('".$fecha_desde."', '%Y-%m')   ";
	        $resultBco= mysql_query($sqlBco);
	        $valor_registrado=0;
	        while($rowBco = mysql_fetch_array($resultBco) ){
	            $valor_registrado = $rowBco['valor_registrado'];
	        }
	        
	             $pdf->Ln();
	        $pdf->Cell(280,10,"Banco :".$row['nombre_Banco'],$borde,1,'L',$relleno);
	     $pdf->Cell(70, 10, "Saldo conciliado: $" . number_format($row['saldoTotal3'], 2), $borde, 0, 'L', $relleno);
	      $pdf->Cell(70, 10, "Valor Registrado: $" . number_format($valor_registrado, 2), $borde, 0, 'L', $relleno);
	      $pdf->Cell(70, 10, "Cuenta Contable: " . $row['codigo'], $borde, 0, 'L', $relleno);
	      
	      $pdf->Cell(70, 10, "Total Mayorizacion: $ " . calculoMayorizacion($fecha_desde,$fecha_hasta, $sesion_id_periodo_contable,$sesion_id_empresa, $bancos_id_plan_cuenta), $borde, 0, 'L', $relleno);
            
           
            $pdf->Ln();
            
	        $sql2 = " SELECT
                 detalle_bancos.`id_detalle_banco` AS detalle_bancos_id_detalle_banco,
                 CONCAT(
        UPPER(
            LEFT(
                detalle_bancos.`tipo_documento`,
                1
            )
        ),
        LOWER(
            SUBSTRING(
                detalle_bancos.`tipo_documento`,
                2
            )
        )
    ) AS detalle_bancos_tipo_documento,
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
     $sql2.= " ORDER BY detalle_bancos.`tipo_documento`,detalle_bancos.`fecha_cobro` asc  " ;
    
                    $result2 = mysql_query($sql2) or die(mysql_error());     
                    // $pdf->Ln();
                    // $pdf->Cell(25,10,'# Fecha',$borde2,0,'L',$relleno2);
                    // // $pdf->Cell(20,10,'Cpte',$borde2,0,'L',$relleno2);
                    // $pdf->Cell(125,10,'Descripcion',$borde2,0,'L',$relleno2);
                    // $pdf->Cell(35,10,'Documento',$borde2,0,'L',$relleno2);
                    // $pdf->Cell(20,10,' Nro.',$borde2,0,'L',$relleno2);
                    // $pdf->Cell(25,10,'Debitos',$borde2,0,'R',$relleno2);
                    // $pdf->Cell(25,10,'Creditos',$borde2,0,'R',$relleno2);
                    // $pdf->Cell(25,10,'Saldo',$borde2,0,'R',$relleno2);
                    // $pdf->Ln();
                    
       
                $sumaDebitos='0';
                $sumaCreditos='0';
                $data2=array();
                $z=0;
                $pdf->SetWidths(array(25, 125,35,20,25, 25, 25));
                $pdf->SetAligns(array('L', 'L', 'L', 'L','R','R','R'));
                $pdf->SetLineHeight(8);
                $saldo =0;
                $tipo_doc ='';
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
                    $data2[$z][]=$debito_actual;
                    $data2[$z][]=$credito_actual;
                    $data2[$z][]=$saldo;
 
                    $sumaDebitos= $sumaDebitos +$debito_actual;
                    $sumaCreditos= $sumaCreditos +$credito_actual;
                    $z++;
            	}   
            $tp_dc='';
            $sumaDeb=0;
            $sumaCre=0;
            $sumaSal=0;
            $conta=0;
            	foreach($data2 as $item)
                {
                    $conta++;
                    
                     if($item[2] !=$tp_dc && $conta>1){ 
                          $pdf->Cell(205,15,'','',0,'R','');
                        $pdf->Cell(25,15,number_format($sumaDeb, 3, '.', ' '),$borde,0,'R',$relleno);
                        $pdf->Cell(25,15,number_format($sumaCre, 3, '.', ' '),$borde,0,'R',$relleno);
                        $pdf->Cell(25,15,number_format($sumaSal, 3, '.', ' '),$borde,0,'R',$relleno);
                	    $pdf->Ln();
                        $pdf->Ln(4); 
                   }
                    if($item[2] !=$tp_dc){
                        $sumaDeb=0;
                        $sumaCre=0;
                        $sumaSal=0;
            
                        $tp_dc = $item[2];
                       $pdf->Ln();
                    $pdf->Cell(25,10,'# Fecha',$borde2,0,'L',$relleno2);
                    // $pdf->Cell(20,10,'Cpte',$borde2,0,'L',$relleno2);
                    $pdf->Cell(125,10,'Descripcion',$borde2,0,'L',$relleno2);
                    $pdf->Cell(35,10,'Documento',$borde2,0,'L',$relleno2);
                    $pdf->Cell(20,10,' Nro.',$borde2,0,'L',$relleno2);
                    $pdf->Cell(25,10,'Debitos',$borde2,0,'R',$relleno2);
                    $pdf->Cell(25,10,'Creditos',$borde2,0,'R',$relleno2);
                    $pdf->Cell(25,10,'Saldo',$borde2,0,'R',$relleno2);
                    $pdf->Ln(); 
                    }
                    
                    
                 $pdf->Ln(2);
                  $sumaDeb= $sumaDeb + $item[4];
                  $sumaCre=$sumaCre + $item[5];
                  $sumaSal=$sumaSal + floatval($item[6]);
                $pdf->Row(array(
                    $item[0],
                    $item[1],
                    $item[2],
                    $item[3],
                    number_format($item[4], 3, '.', ' '),
                    number_format($item[5], 3, '.', ' '),
                    number_format($item[5]-$item[4], 3, '.', ' '),
                  ));
                 
                  
                }
                  $pdf->Cell(205,15,'','',0,'R','');
                        $pdf->Cell(25,15,number_format($sumaDeb, 3, '.', ' '),$borde,0,'R',$relleno);
                        $pdf->Cell(25,15,number_format($sumaCre, 3, '.', ' '),$borde,0,'R',$relleno);
                        $pdf->Cell(25,15,number_format($sumaSal, 3, '.', ' '),$borde,0,'R',$relleno);
                	    $pdf->Ln();
                        $pdf->Ln(4); 

            	
                  $pdf->Cell(205,15,'Total final',$borde,'R',$relleno);
                 
             
                   $pdf->Cell(25,15,number_format($sumaDebitos, 3, '.', ' ') ,$borde,0,'R',$relleno);
                    $pdf->Cell(25,15,number_format($sumaCreditos, 3, '.', ' '),$borde,0,'R',$relleno);
                    $pdf->Cell(25,15,number_format($saldo, 3, '.', ' '),$borde,0,'R',$relleno);
            	    $pdf->Ln();
                    $pdf->Ln(4);
	    }
	    
	    
	    $currentCodImp = $CodImp;
    }
    

    # Nombre del archivo PDF #
    $pdf->Output("I","Retenciones.pdf",true);  
    
  
        


